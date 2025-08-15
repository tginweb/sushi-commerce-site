export function treeBuild<T extends {
    [key in string]?: any
} = {
    [key in string]?: any
}>(dataset: T[], idField: keyof T = 'ID', parentField: keyof T = 'PARENT', childrenField: keyof T = 'CHILDREN', fillOnlyNullChildrens = false) {

    const hashTable = Object.create(null)

    let haveNotNullChildrenField = false

    dataset.forEach((node: any) => {
        haveNotNullChildrenField = haveNotNullChildrenField || !!node[childrenField]
        node[childrenField] = []
        hashTable[node[idField]] = node
    })

    if (haveNotNullChildrenField && fillOnlyNullChildrens)
        return dataset

    const dataTree: any = [];
    dataset.forEach((aData: any) => {

        const child = hashTable[aData[idField]]
        const parentId = child[parentField]

        if (parentId) {
            if (Array.isArray(parentId)) {
                parentId.forEach((id: any) => {
                    if (hashTable[id])
                        hashTable[id][childrenField].push(child)
                })
            } else {
                if (hashTable[parentId])
                    hashTable[parentId][childrenField].push(child)
            }
        } else dataTree.push(child)
    });
    return dataTree;
}

export default treeBuild
