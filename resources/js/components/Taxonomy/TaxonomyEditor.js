import React, {useState} from 'react'
import ReactDOM from 'react-dom'
import TaxonomyList from './partials/TaxonomyList';
import EditButton from './partials/editor/EditButton';
import AddButton from './partials/editor/AddButton';


export default function TaxonomyEditor({tree: originalTree}) {

    const [tree, setTree] = useState(originalTree)

    const changeName = (element, newName) => {
        element.name = newName
        setTree([...tree])
    }

    const addNode = (parent, node) => {

        const whereToAdd = parent ?? tree
        whereToAdd.push(node)
        setTree([...tree])
    };

    const renderEditButton = elm => {
        return (<EditButton element={elm} onUpdate={changeName}/>)
    }

    const renderAddButton = parent => {
        return (<AddButton parent={parent} onCreate={addNode}/>)
    }

    return (
        <TaxonomyList tree={tree} appendList={renderAddButton} appendNode={renderEditButton}/>
    )
}


const el = document.getElementById('taxonomy-editor');
if (el) {
    ReactDOM.render(<TaxonomyEditor tree={JSON.parse(el.dataset.tree)}/>, el);
}
