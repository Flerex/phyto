import React, {Component, useEffect, useState} from 'react'
import ReactDOM from 'react-dom'
import TreeView from './TreeView'
import styles from '../../sass/components/Boxer/HierarchySelector.scss'
import Tippy from '@tippyjs/react';


function HierarchySelector({mode, catalog}) {


    const [data, setData] = useState([])
    const [originalData, setOriginalData] = useState([])
    const [query, setQuery] = useState('')

    const [name, setName] = useState('')
    const [creating, setCreating] = useState(false)
    const [editing, setEditing] = useState(false)
    const [parent, setParent] = useState(null)
    const [element, setElement] = useState(null)
    const [sending, setSending] = useState(false)
    const [error, setError] = useState(null)


    /**
     * When called, uses the state data to send a request and
     * edit anode.
     *
     * The needed state data is:
     *  - The id of the node
     *  - The new name
     *  - The type of the node to be edited
     */
    const edit = () => {
        setSending(true)

        axios.post(route('async.edit_node'), {
            type: element.type,
            id: element.id,
            name: name,
        }).then(({data}) => {
            setSending(false)

            if (!data.success) {
                setError(data.message)
                return
            }

            element.name = data.data.name

            setEditing(false)
        }).catch(e => {
            setSending(false)
            alert(e) // FIXME: Show errors in a user-friendly way

        })
    }

    /**
     * When called, uses the state data to send a request and
     * create a new node.
     *
     * The needed state data is:
     *  - The parent node (so that when can get the id of it)
     *  - The name of the new node
     *  - The type of the node to be created
     */
    const create = () => {
        setSending(true)

        const request = {name, type: 'domain'}

        if (parent) {
            request.type = parent.contains
            request.parent = parent.id
        }

        axios.post(route('async.add_to_hierarchy'), request).then(({data}) => {

            setSending(false)
            if (!data.success) {
                setError(data.message)
                return
            }


            const parentNode = request.type === 'domain' ? data : parent.children;

            parentNode.push({
                id: data.data.id,
                name,
                children: [],
            })


            setCreating(false)

        }).catch(e => {
            setSending(false)
            alert(e) // FIXME: Show errors in a user-friendly way
        })
    }

    const onCreating = elm => {
        setCreating(true)
        setParent(elm)
        setName('')
        setSending(false)
    }

    const onEditing = elm => {
        setEditing(true)
        setElement(elm)
        setName(elm.name)
        setSending(false)
    }


    /**
     * Hides the modal
     */
    const hideModal = _ => {
        setEditing(false)
        setCreating(false)
        setName('')
    }


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
    const handleSearch = e => {
        const query = e.target.value
        setQuery(query)
        setData(query.trim() ? filter(originalData, query) : originalData)
    }

    /**
     * Listener for the keyPress event in the document.
     *
     * Hides the modal if opened.
     *
     * @param event
     */
    const escFunction = (event) => {
        if (event.keyCode === 27) hideModal()
    }

    useEffect(() => {
        document.addEventListener("keydown", escFunction, false)

        const url = mode === 'select' && catalog
            ? route('async.edit_catalog', {catalog})
            : route('async.species')

        axios.get(url)
            .then(r => {
                const tree = r.data
                setData(tree)
                setOriginalData(tree)
            })

        return function cleanup() {
            document.removeEventListener("keydown", escFunction, false);
        }
    }, [])


    const renderError = () => {
        if (!error)
            return;

        return (<span className="has-text-danger">{error}</span>);
    }

    const renderModal = () => {
        return (
            <div className={creating || editing ? 'modal is-active' : 'modal'}>
                <div className="modal-background"/>
                <div className="modal-card">
                    <header className="modal-card-head">
                        <p className="modal-card-title">{creating ? Lang.trans('hierarchy_selector.add_modal_title') : Lang.trans('hierarchy_selector.edit_modal_title')}</p>
                        <button className="delete" aria-label="close" onClick={hideModal}/>
                    </header>
                    <section className="modal-card-body">
                        <input className="input" type="text" placeholder={Lang.trans('labels.name')}
                               value={name}
                               onChange={e => setName(e.target.value)}/>
                    </section>
                    <footer className="modal-card-foot">
                        <button className={sending ? 'button is-success is-loading' : 'button is-success'}
                                onClick={creating ? create : edit}>
                            {creating ? Lang.trans('hierarchy_selector.add_node') : Lang.trans('hierarchy_selector.edit_node')}
                        </button>
                        <button className="button" onClick={hideModal}>{Lang.trans('general.cancel')}</button>
                        {renderError()}
                    </footer>
                </div>
            </div>
        )
    }

    const renderAddButton = elm => {
        if (mode !== 'index')
            return;

        return (<AddButton element={elm} onCreating={onCreating}/>)

    }

    const renderEditButton = elm => {
        if (mode !== 'index')
            return;
        return (<EditButton element={elm} onEditing={onEditing}/>)

    }

    const renderCheckbox = elm => {
        if (mode !== 'select')
            return;

        return (<Checkbox element={elm}/>)
    }

    return (
        <React.Fragment>
            <div className={`box ${styles.hierarchySelector}`}>
                <div className={styles.header}>
                    <p className={styles.heading}>{Lang.trans('hierarchy_selector.hierarchy_selector')}</p>

                    <p className="control has-icons-left">
                        <input className="input is-small" placeholder={Lang.trans('general.search')} type="text"
                               value={query}
                               onChange={handleSearch}/>
                        <span className="icon is-small is-left">
                            <i className="fas fa-search" aria-hidden="true"/>
                        </span>
                    </p>
                </div>
                <TreeView data={data} appendList={renderAddButton}
                          appendNode={mode === 'select' ? renderCheckbox : renderEditButton}/>
            </div>
            {renderModal()}
        </React.Fragment>
    )
}

/**
 * The AddButton component.
 */
class AddButton extends Component {

    constructor(props) {
        super(props)

        this.clicked = this.clicked.bind(this);
    }

    clicked() {
        this.props.onCreating(this.props.element);
    }

    render() {
        return (
            <li className={styles.add_new} onClick={this.clicked}>
                <span className="icon"><i className="fas fa-plus-circle"/></span>
                <span>{Lang.trans('hierarchy_selector.add_new')}</span>
            </li>
        )
    }
}

/**
 * The EditButton component.
 */
class EditButton extends Component {

    constructor(props) {
        super(props)

        this.clicked = this.clicked.bind(this);
    }

    clicked() {
        this.props.onEditing(this.props.element);
    }

    render() {
        return (
            <Tippy content={Lang.trans('hierarchy_selector.edit_node')}>
                <button className={`button is-small is-light is-rounded is-link ${styles.edit_button}`}
                        onClick={this.clicked}>
                    <span className="icon"><i className="fas fa-edit"/></span>
                </button>
            </Tippy>
        )
    }
}

/**
 * The Checkbox component.
 */
class Checkbox extends Component {

    constructor(props) {
        super(props)

        this.state = {
            selected: this.props.element.selected,
        }

        this.changed = this.changed.bind(this);
    }

    changed(e) {
        this.setState({
            selected: !this.state.selected,
        })
    }

    render() {
        return (<input type="checkbox" className={'checkbox ' + styles.checkbox} onChange={this.changed}
                       name={this.props.element.type + '[]'}
                       value={this.props.element.id} checked={this.state.selected}/>)
    }
}


const el = document.getElementById('hierarchy_selector');
if (el) {
    ReactDOM.render(<HierarchySelector mode={el.dataset.mode} catalog={el.dataset.catalog}/>, el);
}
