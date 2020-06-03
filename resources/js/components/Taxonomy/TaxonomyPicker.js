import React, {useEffect, useState} from 'react'
import styles from '../../../sass/components/Taxonomy/TaxonomyPicker.scss'
import TaxonomyList from './partials/TaxonomyList';
import Select from 'react-select';
import {filterTree} from '../../utils/taxonomyUtils';


export default function TaxonomyPicker({tree, catalogs, onPick}) {

    const [selection, setSelection] = useState(null)

    const catalogOptions = catalogs.map(({name: label}, value) => ({label, value}))

    const [currentCatalog, setCurrentCatalog] = useState(catalogOptions[0] ?? null)

    const isInCurrentCatalog = node => !!catalogs[currentCatalog.value].nodes[node.type].find(e => e.id === node.id)

    const renderRadioButton = elm => {
        const isSelected = selection?.id === elm.id && selection?.type === elm.type
        return (<input type="radio" className="radio" onChange={_ => setSelection(elm)} checked={isSelected}/>)
    }

    const renderAfterHeader = () => {

        if (catalogOptions.length < 2)
            return;

        return (
            <div className={styles.catalogSelector}>
                <span>{Lang.trans('labels.projects.catalogs')}</span>
                <Select options={catalogOptions} onChange={c => setCurrentCatalog(c)} defaultValue={currentCatalog}/>
            </div>
        )
    };

    const renderAfterList = () => {
        return (
            <div>
                <button className={styles.selectionButton} onClick={_ => onPick(selection)} disabled={!selection}>
                    {Lang.trans('general.select')}
                </button>
            </div>
        )
    };

    return (
        <TaxonomyList tree={filterTree(tree, isInCurrentCatalog)} afterHeader={renderAfterHeader} afterList={renderAfterList}
                      appendNode={renderRadioButton}/>
    )
}


