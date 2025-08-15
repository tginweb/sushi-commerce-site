import {
    TCodebuildCmdArgs,
    TCodebuildConfig,
    TCodebuildConfigCondition,
    TCodebuildConfigOperationContext,
    TCodebuildFieldInfo,
    TCodebuildMatchPattern,
    TCodebuildOutputFile,
    TCodebuildResult,
    TCodebuildResultFragment,
    TCodebuildResultJsonFragment,
    TCodebuildResultQuery,
    TCodebuildSchema,
} from "@/core/graphql/codebuild/types";
import {IntrospectionField} from "graphql";
import {
    IntrospectionInputValue,
    IntrospectionOutputTypeRef,
    IntrospectionType,
} from "graphql/utilities/getIntrospectionQuery";

import {buildASTSchema, parse} from "graphql/index";
import {introspectionFromSchema} from "graphql/utilities";
//@ts-ignore
import {camelCase, get, map, mergeWith, trim, upperFirst} from "lodash-es";

const fs = require("fs");
const path = require("path");

class Builder {
    args: TCodebuildCmdArgs;
    config: TCodebuildConfig = {} as TCodebuildConfig;
    graphqlSchema: any;
    schema: TCodebuildSchema = {
        types: [],
        typesByName: {},
        typesByTag: [],
        query: [],
        mutation: [],
    };
    result: TCodebuildResult = {
        query: [],
        genqlQuery: [],
        mutation: [],
        fragment: [],
        genqlFragment: [],
    };
    files: TCodebuildOutputFile[] = [];

    constructor(args: Partial<TCodebuildCmdArgs>) {
        this.args = {
            config: "codebuild.ts",
            ...args,
        };
    }

    async run() {
        this.readConfig(this.args.config);

        this.readGraphqlSchema();
        this.buildSchema();

        this.buildFragments();
        this.buildQueries();

        if (this.config.generateFragments) {
            this.buildFragmentsFiles();
        }

        if (this.config.generateQueries) {
            this.buildQueriesFiles();
        }

        if (this.isGenqlEnable()) {
            this.genqlArgsTypeExtract();
            this.cleanupGenqlRuntime();
            if (this.config.genql.generateFragments) {
                this.buildGenqlFragments();
                this.buildGenqlFragmentsFiles();
            }
            if (this.config.genql.generateQueries) {
                this.buildGenqlQueries();
                this.buildGenqlMutations();
            }
        }

        this.buildOperationTemplates();

        /*
            console.log('graphqlSchema', this.graphqlSchema)
            console.log('schema', this.schema)
            console.log('result', this.result)
            console.log('files', this.files)

             */

        this.writeFiles();
    }

    isGenqlEnable() {
        return !!this.config.genql.dir;
    }

    readFileSuggestions(
        suggestions: string[] | string,
        isTypescript = true
    ): [string | undefined, string | undefined] {
        const _suggestions = Array.isArray(suggestions)
            ? suggestions
            : [suggestions];

        for (const suggestion of _suggestions) {
            if (!suggestion) continue;
            try {
                const path = this.getFileRealpath(suggestion, isTypescript);
                if (path) {
                    const content = fs.readFileSync(path, "utf8");
                    if (content) {
                        return [content, path];
                    }
                }
            } catch (e) {
                console.log(e);
            }
        }
        return [undefined, undefined];
    }

    genqlArgTypes: Record<string, string> = {};

    genqlArgTypeCollect(intefaceName: string, fieldName: string, argsType: any) {
        let argsTypeName;
        switch (intefaceName) {
            case "QueryGenqlSelection":
                argsTypeName = this.getArgsTypeName("Query", fieldName);
                break;
            case "MutationGenqlSelection":
                argsTypeName = this.getArgsTypeName("Mutation", fieldName);
                break;
            default:
                argsTypeName = this.getArgsTypeName(
                    intefaceName.replace("GenqlSelection", ""),
                    fieldName
                );
        }
        this.genqlArgTypes[argsTypeName] = trim(argsType, "{}");
        return argsTypeName;
    }

    getGenqlFilePath(name: string) {
        return this.config.genql.dir + "/" + name;
    }

    getDirRealpath(dir: string) {
        return path.resolve(dir);
    }

    getFileRealpath(filepath: string, isTypescript = true) {
        if (!filepath) return null;
        filepath = path.resolve(filepath);
        if (filepath && isTypescript && !filepath.match(/\.ts$/)) {
            filepath += ".ts";
        }
        return filepath;
    }

    genqlArgsTypeExtract() {
        const schemaPath = this.getGenqlFilePath("schema");

        const [content, schemaFilepath] = this.readFileSuggestions(schemaPath);

        if (content) {
            let result = content.replaceAll(
                /(export interface\s+(?<blockName>\w+)\s*)\{(.+?)\}\n/gs,
                (blockContent, ...blockMatches) => {
                    const blockVars = blockMatches.at(-1);

                    //console.log({blockName: blockVars.blockName})

                    return blockContent.replaceAll(
                        /(?<field>\w+)\??\:\s*(\((?<name>\w+) \& \{ \_\_args\??\: (?<args1>\{[^}]*\})|\{ \_\_args\??\: (?<args2>\{[^}]*\}))/g,
                        (fieldContent, ...fieldMatches) => {
                            const fieldVars = fieldMatches.at(-1);

                            const args = fieldVars.args1 || fieldVars.args2;

                            const argsType = this.genqlArgTypeCollect(
                                blockVars.blockName,
                                fieldVars.field,
                                args
                            );

                            return fieldContent.replace(args, argsType);
                        }
                    );
                }
            );

            result = result.replaceAll(
                "__scalar?: boolean | number",
                `__scalar?: boolean | number\n    __fragment?: JsonFragmentName`
            );

            const additional = map(
                this.genqlArgTypes,
                (content: string, name: string) => {
                    return `export interface ${name} { 
    ${content} 
}`;
                }
            ).join("\n");

            fs.writeFileSync(
                schemaFilepath,
                `import {JsonFragmentName} from './fragments'\n\n` +
                result +
                "\n\n" +
                additional
            );
        }

        let [indexContent, indexFilepath] = this.readFileSuggestions(
            this.getGenqlFilePath("index")
        );

        if (indexContent) {
            indexContent = indexContent.replaceAll(
                "./runtime",
                this.config.genql.runtimePath || ""
            );

            fs.writeFileSync(indexFilepath, indexContent);
        }
    }

    matchRules(
        value: string | string[],
        rules: TCodebuildMatchPattern,
        logic: "OR" | "AND" = "OR"
    ) {
        if (typeof rules === "boolean") {
            return rules;
        }

        const valueList = Array.isArray(value) ? value : [value];
        const rulesList = Array.isArray(rules) ? rules : [rules];
        const rulesPatterns = rulesList.map((item) =>
            item.charAt(0) === "/" ? new RegExp(trim(item, "/"), "i") : item
        );

        let match = logic === "AND";

        for (const value of valueList) {
            if (!!rulesPatterns.find((pattern) => value.match(pattern))) {
                if (logic === "OR") {
                    match = true;
                    break;
                }
            } else {
                if (logic === "AND") {
                    match = false;
                    break;
                }
            }
        }

        return match;
    }

    checkInclude(value: string, rule: TCodebuildConfigCondition) {
        let allow: TCodebuildMatchPattern = [];
        let deny: TCodebuildMatchPattern = [];
        let order = rule.order || "allow deny";

        if (typeof rule.allow === "undefined") {
            allow = order === "allow deny";
        } else if (typeof rule.allow === "boolean") {
            allow = rule.allow;
        } else {
            allow = rule.allow;
        }

        if (typeof rule.deny === "undefined") {
            deny = order === "deny allow";
        } else if (typeof rule.deny === "boolean") {
            deny = true;
        } else {
            deny = rule.deny;
        }

        const allowRes = this.matchRules(value, allow);
        const denyRes = this.matchRules(value, deny);

        if (order === "allow deny") {
            return allowRes && !denyRes;
        } else {
            return !denyRes ? true : allowRes;
        }
    }

    flatField(
        field:
            | IntrospectionField
            | IntrospectionInputValue
            | IntrospectionOutputTypeRef,
        nested = false
    ): TCodebuildFieldInfo {
        let info = {} as TCodebuildFieldInfo;

        let fieldType: IntrospectionOutputTypeRef;

        if (!nested) {
            field = field as IntrospectionField;
            info.name = field.name;
            info.args = field.args
                ? field.args.map((item) => this.flatField(item))
                : [];
            fieldType = field.type;
        } else {
            fieldType = field as IntrospectionOutputTypeRef;
        }

        switch (fieldType.kind) {
            case "SCALAR":
                info = {
                    ...info,
                    kind: "SCALAR",
                    type: fieldType.name,
                };
                break;
            case "NON_NULL":
                info = {
                    ...info,
                    notNull: true,
                    ...this.flatField(fieldType.ofType, true),
                };
                break;
            case "LIST":
                info = {
                    ...info,
                    isList: true,
                    ...this.flatField(fieldType.ofType, true),
                };
                break;
            case "OBJECT":
                info = {
                    ...info,
                    kind: "OBJECT",
                    type: fieldType.name,
                };
                break;
        }

        return info;
    }

    buildFieldsTreeJson(
        parentType: TCodebuildFieldInfo,
        usedObjects: Record<string, string> = {},
        parentArgs: null | boolean | Record<string, any> = null
    ) {
        const config = this.config.types[parentType.name];

        const fields = parentType.fields || [];

        const result: any = {};

        for (const field of fields) {
            if (config) {
                if (!this.checkInclude(field.name, config)) continue;
            }

            let argsValue: Record<string, any> = {};

            if (field.args?.length) {
                if (parentArgs) {
                    argsValue = field.args.reduce<Record<string, any>>((map, item) => {
                        if (parentArgs === true) {
                            map[item.name] = "$" + item.name;
                        } else if (Array.isArray(parentArgs)) {
                            if (parentArgs.includes(item.name)) {
                                map[item.name] = "$" + item.name;
                            }
                        } else if (parentArgs[item.name]) {
                            map[item.name] = parentArgs[item.name];
                        }
                        return map;
                    }, {});
                } else {
                    const argsFromConfig: any =
                        get(this.config.overrides, [
                            parentType.name,
                            field.name,
                            "_args",
                        ]) || {};

                    argsValue = field.args.reduce<Record<string, any>>((map, arg) => {
                        let v;
                        if (argsFromConfig[arg.name]) {
                            v = argsFromConfig[arg.name];
                        } else if (arg.notNull) {
                            v = this.getDefaultArgValue(arg);
                        }
                        if (typeof v !== "undefined") map[arg.name] = v;
                        return map;
                    }, {});
                }
            }

            if (field.kind === "SCALAR" && !Object.keys(argsValue).length) {
                result[field.name] = true;
            } else {
                result[field.name] = {};
                if (Object.keys(argsValue).length) {
                    result[field.name].__args = argsValue;
                }
                if (field.kind === "OBJECT") {
                    result[field.name].__fragment = field.type;
                }
            }
        }

        return result;
    }

    buildFieldsTreeStrings(
        parentType: TCodebuildFieldInfo,
        usedObjects: Record<string, string> = {},
        parentArgs: null | boolean | Record<string, any> = null
    ) {
        const config = this.config.types[parentType.name];

        const fields = parentType.fields || [];

        const result = [];

        for (const field of fields) {
            if (config) {
                if (!this.checkInclude(field.name, config)) continue;
            }

            let argsValue: Record<string, any> = {};

            if (field.args?.length) {
                if (parentArgs) {
                    argsValue = field.args.reduce<Record<string, any>>((map, item) => {
                        if (parentArgs === true) {
                            map[item.name] = "$" + item.name;
                        } else if (Array.isArray(parentArgs)) {
                            if (parentArgs.includes(item.name)) {
                                map[item.name] = "$" + item.name;
                            }
                        } else if (parentArgs[item.name]) {
                            map[item.name] = parentArgs[item.name];
                        }
                        return map;
                    }, {});
                } else {
                    const argsFromConfig: any =
                        get(this.config.overrides, [
                            parentType.name,
                            field.name,
                            "_args",
                        ]) || {};

                    argsValue = field.args.reduce<Record<string, any>>((map, arg) => {
                        let v;
                        if (argsFromConfig[arg.name]) {
                            v = argsFromConfig[arg.name];
                        } else if (arg.notNull) {
                            v = this.getDefaultArgValue(arg);
                        }
                        if (typeof v !== "undefined") map[arg.name] = JSON.stringify(v);
                        return map;
                    }, {});
                }
            }

            let argsItemsStr = Object.keys(argsValue).length
                ? "(" +
                Object.keys(argsValue)
                    .map((item) => item + ": " + argsValue[item].toString())
                    .join(", ") +
                ")"
                : "";

            //console.log('fieldArgs', field.args)

            let fieldContent;

            if (field.kind === "SCALAR") {
                fieldContent = "        " + `${field.name}${argsItemsStr}`;
            } else if (field.type) {
                usedObjects[field.type] = field.type;
                fieldContent =
                    "        " +
                    `${field.name}${argsItemsStr} {
            ...${field.type}
        }`;
            }

            if (fieldContent) result.push(fieldContent);
        }

        return result;
    }

    getDefaultArgValue(field: TCodebuildFieldInfo) {
        let defval;

        if (field.kind === "OBJECT") {
            defval = {};
        } else {
            switch (field.type) {
                case "String":
                    defval = "";
                    break;
                case "Boolean":
                    defval = false;
                    break;
                case "Int":
                case "Float":
                case "BigInt":
                    defval = 0;
                    break;
                case "JSON":
                    defval = {};
                    break;
            }
        }
        return defval;
    }

    buildSchema() {
        const scanner = (types: ReadonlyArray<IntrospectionType>) => {
            for (const type of types) {
                if (type.kind === "OBJECT") {
                    if (type.name.match(/^__/)) {
                        continue;
                    }
                    switch (type.name) {
                        case "Mutation":
                            for (const query of type.fields) {
                                this.schema.mutation.push({
                                    name: query.name,
                                    args: query.args.map((item) => this.flatField(item)),
                                    fields: [this.flatField(query)],
                                });
                            }
                            break;
                        case "Query":
                            for (const query of type.fields) {
                                this.schema.query.push({
                                    name: query.name,
                                    args: query.args.map((item) => this.flatField(item)),
                                    fields: [this.flatField(query)],
                                });
                            }
                            break;
                        default:
                            this.addSchemaType({
                                name: type.name,
                                fields: type.fields.map((field) => this.flatField(field)),
                            });
                    }
                }
            }
        };
        scanner(this.graphqlSchema.__schema.types);
    }

    addSchemaType(info: TCodebuildFieldInfo) {
        this.schema.types.push(info);
        this.schema.typesByName[info.name] = info;
    }

    buildOperationTemplates() {
        const candidates = [...this.result.query, ...this.result.mutation];

        const items: any = [];

        for (const candidate of candidates) {
            const typeConfig = this.config.types[candidate.name] || {};

            for (const template of this.config.operationTemplates) {
                const conditions = [
                    [candidate.type, template.condition.type],
                    [candidate.name, template.condition.name],
                    [typeConfig.tag, template.condition.tags],
                ];

                if (template.condition.entityType === true && !typeConfig.entityType) {
                    continue;
                }

                //console.log('conditions', conditions)

                let match = true;

                for (const condition of conditions) {
                    if (condition[0] && condition[1]) {
                        if (!this.matchRules(condition[0], condition[1], "OR")) {
                            match = false;
                            break;
                        }
                    }
                }

                //console.log('match', match)

                if (match) {
                    if (template.template) {
                        const context = {
                            ...candidate,
                            config: this.config,
                            typeConfig,
                        } as TCodebuildConfigOperationContext;

                        const file = {
                            filename: template.filename(context),
                            filedir: template.filedir
                                ? template.filedir(context)
                                : this.config.queriesDir,
                            content: template.template(context),
                        } as TCodebuildOutputFile;

                        this.files.push(file);
                    }
                }
            }
        }
    }

    indent(level: number, char = "  ") {
        let offset = "";
        for (let i = 0; i < level; i++) {
            offset += char;
        }
        return offset;
    }

    buildVarsType(
        info: TCodebuildFieldInfo,
        fields: TCodebuildFieldInfo[],
        tsType: string,
        depth = 0,
        parentTypes: string[] = [],
        usedTypes: any = {}
    ) {
        let varsType = info.args?.length ? tsType : "";

        const parentType = parentTypes[parentTypes.length - 1];

        const fieldsVars: any = {};

        for (const field of fields) {
            let fieldVarsType: any;

            if (field.kind === "OBJECT") {
                if (field.type && !parentTypes.includes(field.type)) {
                    const subType = this.schema.typesByName[field.type];
                    fieldVarsType = this.buildVarsType(
                        field,
                        subType?.fields || [],
                        this.getArgsTypeName(parentType || "", field.name),
                        depth + 1,
                        [...parentTypes, field.type],
                        usedTypes
                    );
                }
            } else {
                fieldVarsType = this.buildVarsType(
                    field,
                    [],
                    this.getArgsTypeName(parentType || "", field.name),
                    depth + 1,
                    parentTypes,
                    usedTypes
                );
            }

            //console.log('fieldVarsType', fieldVarsType)

            if (fieldVarsType) {
                fieldsVars[field.name] = fieldVarsType;
            }
        }

        if (varsType) usedTypes[varsType] = varsType;

        if (Object.keys(fieldsVars).length) {
            if (varsType) {
                varsType += " & ";
            }
            const required = false; // info.notNull && (depth === 0)
            varsType += `{
${Object.keys(fieldsVars)
                .map(
                    (field) =>
                        `${this.indent(depth + 3)}${field}${!required ? "?" : ""}: ${
                            fieldsVars[field]
                        }`
                )
                .join("\n")}${this.indent(depth + 1)}
${this.indent(depth)}}`;
        }

        return varsType || "{}";
    }

    camelCaseName(name: string, ucFirst = false) {
        return ucFirst ? upperFirst(camelCase(name)) : camelCase(name);
    }

    getArgsTypeName(parentType: string, fieldName: string) {
        return this.camelCaseName(parentType + " " + fieldName + "Args", true);
    }

    getBuilderPath(subpath?: string) {
        return subpath ? path.join(__dirname, subpath) : __dirname;
    }

    copyGenqlTemplates() {
        const files = {
            "genql-builder.ts": "builder.ts",
        };
        for (const [srcName, destName] of Object.entries(files)) {
            const filepath = this.getDirRealpath(
                this.getBuilderPath("/templates/" + srcName + "")
            );
            fs.copyFileSync(filepath, this.config.genql.dir + "/" + destName);
        }
    }

    cleanupGenqlRuntime() {
        fs.rmSync(this.config.genql.dir + "/runtime", {
            recursive: true,
            force: true,
        });
    }

    buildGenqlMutations() {
        for (const query of this.schema.mutation) {
            const parts: string[] = [];

            const queryType = this.schema.typesByName[query.name];


            const fields = queryType ? queryType.fields || [] : [];

            const queryName = this.camelCaseName(query.name, false);
            const queryNameType = this.camelCaseName(query.name, true);

            const queryFileName = queryName;
            const queryArgsTypeName = "Mutation" + queryNameType + "Args";
            const queryVarsTypeName = "Mutation" + queryNameType + "Vars";

            const queryResultTypeName = query.fields?.[0]?.type
            //const queryResultTypeName = queryNameType + "Result";

            const queryFragmentName = queryName + "Fragment";

            const usedTypes = {};

            const typeDefinition = this.buildVarsType(
                query,
                fields,
                queryArgsTypeName,
                0,
                [],
                usedTypes
            );

            parts.push(
                `import {genqlBuilder} from "${this.config.genql.builderPath}"`
            );
            parts.push(
                `import {JsonFragmentName${
                    fields.length ? ", " + queryFragmentName : ""
                }} from "../fragments"`
            );
            parts.push(
                `import {MutationGenqlSelection, ${queryResultTypeName}} from "../schema"`
            );

            if (Object.keys(usedTypes).length) {
                parts.push(
                    `import {${Object.keys(usedTypes).join(", ")}} from "../schema"`
                );
            }

            if (typeDefinition) {
                parts.push(`export type ${queryVarsTypeName} = ${typeDefinition}`);
            }

            const defaultQuery = fields.length
                ? `{${queryName}: ${queryFragmentName}}`
                : `{__scalar: true}`;

            parts.push(`export function ${queryName}Mutation(mutation: MutationGenqlSelection['${query.name}'] | null) {            
    return {
      build: (variables: ${queryVarsTypeName}) => genqlBuilder.build(mutation ? {${query.name}: mutation} : ${defaultQuery}, {${query.name}: variables}),  
      mutate: (variables: ${queryVarsTypeName}) => ({} as Promise<${queryResultTypeName}>),
      _variables: {} as ${queryVarsTypeName},
      _result: {} as ${queryResultTypeName},
    }
}`);

            parts.push(`export type Mutation${queryNameType} = {
  builder: (variables: ${queryVarsTypeName}) => MutationGenqlSelection,
  selection: MutationGenqlSelection['${query.name}'],
  variables: ${queryVarsTypeName},
  result: ${queryResultTypeName}
}`);

            parts.push(`export default ${queryName}Mutation`);

            const file = {
                filename: queryFileName + "Mutation.ts",
                filedir: this.config.genql.dir + "/mutation",
                content: parts.join("\n\n"),
            } as TCodebuildOutputFile;

            this.files.push(file);
        }
    }

    buildGenqlQueries() {
        for (const query of this.schema.query) {
            const parts: string[] = [];

            const queryType = this.schema.typesByName[query.name];
            const fields = queryType ? queryType.fields || [] : [];

            const queryName = this.camelCaseName(query.name, false);
            const queryNameType = this.camelCaseName(query.name, true);

            const queryFileName = queryName;
            const queryArgsTypeName = "Query" + queryNameType + "Args";
            const queryVarsTypeName = "Query" + queryNameType + "Vars";
            const queryResultTypeName = queryNameType + "Result";

            const queryFragmentName = queryName + "Fragment";

            const usedTypes = {};

            const typeDefinition = this.buildVarsType(
                query,
                fields,
                queryArgsTypeName,
                0,
                [],
                usedTypes
            );

            parts.push(
                `import {useGraphql} from "@/core/graphql/service"`,
                `import {genqlBuilder} from "${this.config.genql.builderPath}"`
            );

            parts.push(
                `import {JsonFragmentName${
                    fields.length ? ", " + queryFragmentName : ""
                }} from "../fragments"`
            );

            parts.push(`import {QueryGenqlSelection, Query} from "../schema"`);

            if (Object.keys(usedTypes).length) {
                parts.push(
                    `import {${Object.keys(usedTypes).join(", ")}} from "../schema"`
                );
            }

            if (typeDefinition) {
                parts.push(`export type ${queryVarsTypeName} = ${typeDefinition}`);
            }

            parts.push(`export type ${queryResultTypeName} = Query['${query.name}']`);

            const defaultQuery = fields.length
                ? `{${queryName}: ${queryFragmentName}}`
                : `{__scalar: true}`;

            parts.push(`export function ${queryName}Query(query: QueryGenqlSelection['${query.name}'] | null) {
      
    const build = (variables: ${queryVarsTypeName}) => genqlBuilder.build(query ? {${query.name}: query} : ${defaultQuery}, {${query.name}: variables})
    const run = (variables: ${queryVarsTypeName} | null = null): Promise<${queryResultTypeName} | null> => {
        const {queryWrapped} = useGraphql()
        return queryWrapped(build(variables || {}))
    }
                
    return {
      build,  
      run,
      _variables: {} as ${queryVarsTypeName},
      _result: {} as ${queryResultTypeName},
    }
}`);

            parts.push(`export default ${queryName}Query`);

            const file = {
                filename: queryFileName + "Query.ts",
                filedir: this.config.genql.dir + "/query",
                content: parts.join("\n\n"),
            } as TCodebuildOutputFile;

            this.files.push(file);
        }
    }

    buildQueries() {
        for (const type of this.schema.query) {
            let resultType = null;

            if (type.fields && type.fields[0]) {
                resultType = type.fields[0].type;
            }

            const nameTyped = this.camelCaseName(type.name, true);
            const nameCamel = this.camelCaseName(type.name);

            const query: TCodebuildResultQuery = {
                type: "query",
                name: type.name,
                nameTyped: nameTyped,
                nameCamel: nameCamel,
                variables: [],
                fields: [],
                resultType: resultType || "",
                content: "",
                deps: [],
            };

            const deps = {};

            query.fields = this.buildFieldsTreeStrings(type, deps, true);

            query.deps = Object.keys(deps).reduce<Record<string, any>>(
                (map, name) => {
                    map[name] = {};
                    if (this.config.overrides[name]) {
                        map[name] = this.config.overrides[name];
                    }
                    return map;
                },
                {}
            );

            let argsDefStr = "";

            if (type.args?.length) {
                const argsDef = type.args.map((item) => {
                    let defval = !item.notNull
                        ? this.getDefaultArgValue(item)
                        : undefined;
                    return (
                        "        $" +
                        item.name +
                        ": " +
                        item.type +
                        (typeof defval !== "undefined"
                            ? " = " + JSON.stringify(defval)
                            : "")
                    );
                });

                argsDefStr = argsDef.length
                    ? `(
` +
                    argsDef.join(",\n") +
                    `
    )`
                    : "";
            }

            query.content = `query${argsDefStr} {        
${query.fields.join("\n")}
    }`;

            this.result.query.push(query);
        }
    }

    buildFragments() {
        for (const info of this.schema.types) {
            const fragment: TCodebuildResultFragment = {
                name: info.name,
                fields: [],
                content: "",
                deps: [],
            };

            const deps = {};

            fragment.fields = this.buildFieldsTreeStrings(info, deps);

            if (!fragment.fields.length) {
                continue;
            }

            fragment.deps = Object.keys(deps);
            fragment.content = `fragment ${info.name} on ${info.name}  {
${fragment.fields.join("\n")}
    }`;
            this.result.fragment.push(fragment);
        }
    }

    buildFragmentsFiles() {
        for (const fragment of this.result.fragment) {
            const file = {
                filename: fragment.name + "Fragment.ts",
                filedir: this.config.fragmentsDir,
            } as TCodebuildOutputFile;

            file.imports = [
                `import {fragment, gql} from "${this.config.generateLib}"`,
                ...fragment.deps.map(
                    (item) => `import ${item}Fragment from './${item}Fragment'`
                ),
            ];

            file.nestedFragments = fragment.deps.reduce<Record<string, string>>(
                (map, name) => {
                    map[name] = name + "Fragment";
                    return map;
                },
                {}
            );

            file.content = `${file.imports.join("\n")}       

export default fragment(   
    gql\`${fragment.content}\`
, () => ({${Object.values(file.nestedFragments)}}))
`;
            this.files.push(file);
        }
    }

    buildGenqlFragments() {
        for (const info of this.schema.types) {
            const fragment: TCodebuildResultJsonFragment = {
                name: info.name,
                fields: [],
                content: "",
                deps: [],
            };
            const deps = {};
            fragment.fields = this.buildFieldsTreeJson(info, deps);
            if (!Object.keys(fragment.fields).length) {
                continue;
            }
            fragment.content = JSON.stringify(fragment.fields, null, 2);
            this.result.genqlFragment.push(fragment);
        }

        if (this.config.genql.customFragmentsDir) {
            const dir = path.resolve(this.config.genql.customFragmentsDir);
            const files = fs.readdirSync(dir);
            for (const file of files) {
                const [name] = file.split(".");
                const content = fs.readFileSync(dir + "/" + file, "utf8");
                if (!content) continue;
                const fragment: TCodebuildResultJsonFragment = {
                    name: name,
                    fields: [],
                    import: `import {${name}} from '@/gql/fragments/${name}'`,
                    deps: [],
                };
                this.result.genqlFragment.push(fragment);
            }
        }
    }

    buildGenqlFragmentsFiles() {
        const contents = [];
        const fragmentsMap = {};

        for (const fragment of this.result.genqlFragment) {
            if (fragment.import) {
                contents.push(fragment.import);
            }
            if (fragment.content) {
                const fragmentContent = `const ${fragment.name} = ${fragment.content}`;
                contents.push(fragmentContent);
            }
        }

        for (const fragment of this.result.genqlFragment) {
            const fragmentContent = `export const ${fragment.name}Fragment = ${fragment.name}`;
            contents.push(fragmentContent);
        }

        contents.push(
            "export const fragments = " +
            `{
${this.result.genqlFragment.map((item) => "    " + item.name).join(",\n")}
}`
        );

        contents.push(
            "export type JsonFragmentName = " +
            this.result.genqlFragment.map((item) => `'${item.name}'`).join(" | ")
        );

        const file = {
            filename: "fragments.ts",
            filedir: this.config.genql.dir,
            content: contents.join("\n"),
        } as TCodebuildOutputFile;

        this.files.push(file);
    }

    buildQueriesFiles() {
        for (const query of this.result.query) {
            const file = {
                filename: query.name + ".ts",
                filedir: this.config.queriesDir,
            } as TCodebuildOutputFile;

            const imports = [
                `import {gql} from "@apollo/client"`,
                ...Object.keys(query.deps).map(
                    (item) =>
                        `import ${item}Fragment from '${this.config.fragmentsDir}/${item}Fragment'`
                ),
            ];

            const interpolations = Object.keys(query.deps).map((name) => {
                const conf = query.deps[name] ? JSON.stringify(query.deps[name]) : "";
                return "${" + name + `Fragment(${conf})}`;
            });

            file.content = `
${imports.join("\n")}       

export default gql\`
    ${query.content}
    ${interpolations.join("\n")}
\``;

            this.files.push(file);
        }
    }

    writeFiles() {
        for (const file of this.files) {
            const dir = path.resolve(file.filedir);

            if (!fs.existsSync(dir) && this.config.createDirs) {
                //console.log('dir', dir)

                fs.mkdirSync(dir, {recursive: true});
            }

            fs.writeFileSync(dir + "/" + file.filename, file.content);
        }
    }

    readConfig(file: string) {
        const config = require(path.resolve(file)).default;

        this.config = mergeWith(
            {
                createDirs: false,
                overrides: {},
                types: {},
                operationTemplates: [],
                generateFragments: true,
                generateQueries: true,
                genql: {
                    generateFragments: true,
                    generateQueries: true,
                    generateMutation: true,
                    argsTypeExtract: true,
                },
            } as Partial<TCodebuildConfig>,
            config
        );

        if (!this.config.fragmentsDir)
            this.config.fragmentsDir = this.config.outputDir + "/fragments";

        if (!this.config.queriesDir)
            this.config.queriesDir = this.config.outputDir + "/queries";

        if (!this.config.typesPath)
            this.config.typesPath = this.config.outputDir + "/graphql";
    }

    readGraphqlSchema() {
        if (!this.config.schema) throw new Error('"schema" option is empty');

        const content = fs.readFileSync(this.config.schema, "utf8");

        if (!content) throw new Error("schema is empty");

        const schema = buildASTSchema(parse(content));
        this.graphqlSchema = introspectionFromSchema(schema, {
            schemaDescription: true,
        });
    }
}

const builder = new Builder(require("minimist")(process.argv.slice(2)));

builder.run();
