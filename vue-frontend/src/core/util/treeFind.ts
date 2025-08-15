type TreeNode<T> = T & {
    [key: string]: any; // Для поддержки произвольных ключей children
};

export function treeFind<T>(
    tree: TreeNode<T>[],
    predicate: (node: TreeNode<T>) => boolean | undefined | null,
    childrenKey: string = 'children'
): TreeNode<T> | undefined {
    for (const node of tree) {
        // Проверяем текущий узел
        if (predicate(node)) {
            return node;
        }

        // Рекурсивно проверяем дочерние узлы
        if (node[childrenKey]?.length) {
            const found = treeFind(node[childrenKey], predicate, childrenKey);
            if (found) {
                return found;
            }
        }
    }

    return undefined;
}
