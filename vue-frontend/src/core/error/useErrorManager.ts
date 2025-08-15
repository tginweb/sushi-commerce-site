import { ErrorInterface } from "@/gql/gen";
import { get, groupBy } from "lodash-es";
import { computed, ref } from "vue";
import { getTypedEntries } from "../util/getTypedEntries";

export type ErrorGroupsSchema = Record<
  string,
  keyof ErrorInterface | (keyof ErrorInterface)[] | any
>;

export type UseErrorManagerParams = {
  groupsSchema?: ErrorGroupsSchema;
  onFieldChangeClear?: boolean;
};

export type UseErrorManager = ReturnType<typeof useErrorManager>;

export default function useErrorManager(
  errors: ErrorInterface | ErrorInterface[] = [],
  params: UseErrorManagerParams = {}
) {
  const { groupsSchema = {}, onFieldChangeClear = true } = params;

  const errorsRef = ref<ErrorInterface[]>(
    Array.isArray(errors) ? errors : [errors]
  );

  const groupsSchemaRef = ref(groupsSchema);

  const groups = computed<
    Record<keyof typeof groupsSchema | string, Record<string, ErrorInterface[]>>
  >(() => {
    let map: any = {};
    if (groupsSchemaRef.value) {
      for (const [groupName, groupSelector] of getTypedEntries(
        groupsSchemaRef.value
      )) {
        map[groupName] = groupBy(errorsRef.value, groupSelector);
      }
    }
    return map;
  });

  const setError = (error: ErrorInterface) => {
    errorsRef.value = [error];
  };

  const setErrors = (errors: ErrorInterface[]) => {
    errorsRef.value = errors;
  };

  const setGroupsSchema = (schema: ErrorGroupsSchema = {}) => {
    groupsSchemaRef.value = schema;
  };

  const clear = () => {
    errorsRef.value = [];
  };

  const clearByGroup = (groupName: string, subgroupName?: string) => {
    let targetErrors: ErrorInterface[] = [];

    if (groupName && subgroupName) {
      targetErrors = get(groups.value, [groupName, subgroupName]) || [];
    } else {
      const subgroups = groups.value[groupName];
      if (subgroups) {
        for (const [subgroupsName, errors] of getTypedEntries(subgroups)) {
          targetErrors.push(...errors);
        }
      }
    }

    if (targetErrors.length) {
      errorsRef.value = errorsRef.value.filter(
        (error) => !targetErrors.includes(error)
      );
    }
  };

  const onFieldChange = (name: string) => {
    if (onFieldChangeClear) {
      clearByGroup('fieldName', name);
    }
  };

  return {
    errorsRef,
    setErrors,
    setError,
    setGroupsSchema,
    onFieldChange,
    clear,
    clearByGroup,
    groups,
  };
}
