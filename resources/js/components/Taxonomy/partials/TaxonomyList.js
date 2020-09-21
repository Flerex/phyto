import React, {useEffect, useState} from 'react'
import TreeView from './TreeView'
import styles from '../../../../sass/components/Taxonomy/TaxonomyList.scss'
import {filterTree} from '../../../utils/taxonomyUtils';


export default function TaxonomyList({tree: originalTree, ...props}) {

    const [tree, setTree] = useState(originalTree)

    const [query, setQuery] = useState('')

    useEffect(() => {
        setTree(query.trim() ? filterTree(originalTree, n => isAMatch(n.name, query)) : originalTree)
    }, [originalTree])


    /**
     * Checks if a string matches with the query.
     *
     * @param string The string to compare
     * @param query The query to be compared against
     * @returns {boolean}
     */
    const isAMatch = (string, query) => string.toLowerCase().indexOf(query.toLowerCase()) !== -1;

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
        setTree(query.trim() ? filterTree(originalTree, n => isAMatch(n.name, query)) : originalTree)
    }

    const renderAfterHeaderEvent = () => {
        if (!props.afterHeader) return;

        return props.afterHeader()
    };
    const renderAfterListEvent = () => {
        if (!props.afterList) return;

        return props.afterList()
    };

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
                {renderAfterHeaderEvent()}
                <div className={styles.treeView}>
                    <TreeView tree={tree} {...props} />
                </div>
                {renderAfterListEvent()}
            </div>
        </React.Fragment>
    )
}
