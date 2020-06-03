/**
 * Recursively filters a tree showing only the nodes that are hold a condition given by a discriminator function
 * and its children.
 * @param tree The tree
 * @param discriminator
 * @returns array
 */
export const filterTree = (tree, discriminator) =>
    tree.reduce((acc, node) => {
        if (discriminator(node)) {
            acc.push(node)
        } else if (node.children && node.children.length) {
            const validNodes = filterTree(node.children, discriminator)

            if (validNodes.length) {
                const {children, ...newNode} = node

                newNode.children = validNodes
                acc.push(newNode)
            }
        }

        return acc

    }, [])
