type TreeNode<T = unknown> = T & {
    [key: string]: any;
};


/**
 * Фильтрует дерево, сохраняя структуру и контролируя поведение исключения потомков
 * @param tree Исходное дерево
 * @param predicate Условие фильтрации
 * @param childrenKey  ключ дочерних элементов (по умолчанию 'children')
 * @param excludeChildrenIfParentExcluded  исключать потомков если родитель исключен (по умолчанию false)
 * @returns Новое отфильтрованное дерево
 */
export function treeFilter<T>(
    tree: TreeNode<T>[],
    predicate: (node: TreeNode<T>) => boolean,
    childrenKey: string = 'children',
    excludeChildrenIfParentExcluded = false
): TreeNode<T>[] {

    return tree.reduce((acc, originalNode) => {
        // Создаем копию узла для иммутабельности
        const node = {...originalNode};

        // Проверяем условие для текущего узла
        const nodeMatches = predicate(node);

        // Обрабатываем дочерние элементы
        let children: TreeNode<T>[] = [];
        if (node[childrenKey]?.length) {
            children = treeFilter(
                node[childrenKey],
                predicate,
                childrenKey,
                excludeChildrenIfParentExcluded
            );
        }

        // Логика исключения узлов
        if (excludeChildrenIfParentExcluded) {
            // Если родитель исключен - игнорируем весь подграф
            if (!nodeMatches) return acc;

            // Добавляем узел с отфильтрованными детьми
            acc.push({...node, [childrenKey]: children});
            return acc;
        }

        // Сохраняем узел если:
        // 1. Он удовлетворяет условию ИЛИ
        // 2. Есть отфильтрованные дочерние элементы
        if (nodeMatches || children.length > 0) {
            acc.push({...node, [childrenKey]: children});
        }

        return acc;
    }, [] as TreeNode<T>[]);
}
