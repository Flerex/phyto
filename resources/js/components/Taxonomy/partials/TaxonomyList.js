import React, {useState} from 'react'
import TreeView from './TreeView'
import styles from '../../../../sass/components/Taxonomy/TaxonomyList.scss'


export default function TaxonomyList({tree: originalTree, ...props}) {


    const [tree, setTree] = useState(originalTree)

    const [query, setQuery] = useState('')


    /**
     * Checks if a string matches with the query.
     *
     * @param string The string to compare
     * @param query The query to be compared against
     * @returns {boolean}
     */
    const isAMatch = (string, query) => string.toLowerCase().indexOf(query.toLowerCase()) !== -1;


    /**
     * Recursively filters a tree showing only the nodes
     * that match the query and its children.
     * @param tree The tree
     * @param query The query
     * @returns array
     */
    const filter = (tree, query) =>
        tree.reduce((acc, node) => {
            if (isAMatch(node.name, query)) {
                acc.push(node)
            } else if (node.children && node.children.length) {
                const validNodes = filter(node.children, query)

                if (validNodes.length) {
                    const {children, ...newNode} = node

                    newNode.children = validNodes
                    acc.push(newNode)
                }
            }

            return acc

        }, [])


    /**
     * Listener for the onChange event of the search field.
     *
     * It takes care of filtering the tree.
     *
     * @param e
     */
    const handleSearch = ({target}) => {
        const query = target.value
        setQuery(query)
        setTree(query.trim() ? filter(originalTree, query) : originalTree)
    }

    return (
        <React.Fragment>
            <div className={'box ' + styles.list}>
                <div className={styles.header}>
                    <p className={styles.heading}>{Lang.trans('taxonomy.title')}</p>
                    <p className="control has-icons-left">
                        <input className="input is-small" type="text" value={query} onChange={handleSearch}
                               placeholder={Lang.trans('general.search')}/>
                        <span className="icon is-small is-left">
                            <i className="fas fa-search" aria-hidden="true"/>
                        </span>
                    </p>
                </div>
                <div>
                    <TreeView tree={tree} {...props} />
                </div>
            </div>
        </React.Fragment>
    )
}
