import React, {useState} from 'react'
import ReactDOM from 'react-dom'
import styles from '../../../sass/components/Taxonomy/TaxonomySelector.scss'
import TaxonomyList from './partials/TaxonomyList';


export default function TaxonomySelector({tree, nodes}) {
    const renderCheckbox = elm => {
        if (!nodes) {
            return (<Checkbox element={elm}/>)
        }

        const isPartOfCatalog = !!nodes[elm.type].find(el => elm.id === el.id);

        return (<Checkbox element={elm} startsSelected={isPartOfCatalog}/>)
    }

    return (
        <TaxonomyList tree={tree} appendNode={renderCheckbox}/>
    )
}


/**
 * The Checkbox component.
 */
function Checkbox({element, startsSelected: initialState = false}) {

    const [checked, setChecked] = useState(initialState)

    const toggled = ({checked}) => {
        setChecked(checked)
    }

    return (
        <input type="checkbox" className={'checkbox ' + styles.checkbox} onChange={toggled} name={element.type + '[]'}
               value={element.id} checked={checked}/>
    )

}

const el = document.getElementById('taxonomy-selector');
if (el) {
    ReactDOM.render(<TaxonomySelector tree={JSON.parse(el.dataset.tree)}
                                      nodes={JSON.parse(el.dataset.nodes ?? null)}/>, el);
}
