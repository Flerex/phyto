import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import TreeView from './TreeView'
import styles from '../../sass/components/HierarchySelector.scss'
import Tippy from '@tippyjs/react';


export default class HierarchySelector extends Component {


    constructor(props) {
        super(props)

        this.escFunction = this.escFunction.bind(this)
        this.renderAddButton = this.renderAddButton.bind(this)
        this.renderEditButton = this.renderEditButton.bind(this)
        this.renderRadioButton = this.renderRadioButton.bind(this)
        this.renderCheckbox = this.renderCheckbox.bind(this)
        this.renderError = this.renderError.bind(this)
        this.hideModal = this.hideModal.bind(this)
        this.create = this.create.bind(this)
        this.edit = this.edit.bind(this)
        this.onCreating = this.onCreating.bind(this)
        this.onEditing = this.onEditing.bind(this)
        this.handleSearch = this.handleSearch.bind(this)
        this.handleRadioSelection = this.handleRadioSelection.bind(this)

        this.state = {
            data: [],
            originalData: [],
            query: '',

            name: '',
            creating: false,
            editing: false,
            parent: null,
            sending: false,

            selection: null,
        }
    }

    /**
     * When called, uses the state data to send a request and
     * edit anode.
     *
     * The needed state data is:
     *  - The id of the node
     *  - The new name
     *  - The type of the node to be edited
     */
    edit() {
        this.setState({
            sending: true,
        })

        const element = this.state.element;

        axios.post('/async/hierarchy/edit', {
            type: element.type,
            id: element.id,
            name: this.state.name,
        }).then(({data}) => {

            if (!data.success) {
                this.setState({
                    error: data.message,
                    sending: false,
                })

                return

            }


            element.name = data.data.name

            this.setState({
                editing: false,
                sending: false,
            })

        }).catch(e => {

            // FIXME: Show errors in a user-friendly way
            alert(e)

            this.setState({
                sending: false,
            })

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
    create() {
        this.setState({
            sending: true,
        })


        const parent = this.state.parent,

            request = {
                name: this.state.name,
                type: 'domain',
            }

        if (parent) {
            request.type = parent.contains
            request.parent = parent.id
        }

        axios.post('/async/hierarchy/add', request).then(({data}) => {

            if (!data.success) {
                this.setState({
                    error: data.message,
                    sending: false,
                })

                return

            }


            const parentNode = request.type === 'domain' ? this.state.data : parent.children;

            parentNode.push({
                id: data.data.id,
                name: this.state.name,
                children: [],
            })


            this.setState({
                creating: false,
                sending: false,
            })

        }).catch(e => {

            // FIXME: Show errors in a user-friendly way
            alert(e)

            this.setState({
                sending: false,
            })

        })
    }

    onCreating(element) {
        this.setState({
            creating: true,
            parent: element,
            name: '',
            sending: false,
        })

    }

    onEditing(element) {
        this.setState({
            editing: true,
            element: element,
            name: element.name,
            sending: false,
        })
    }


    /**
     * Hides the modal
     * @param e
     */
    hideModal(e) {
        this.setState({
            editing: false,
            creating: false,
            name: '',
            error: '',
        })
    }


    /**
     * Checks if a string matches with the query.
     *
     * @param string The string to compare
     * @param query The query to be compared against
     * @returns {boolean}
     */
    isAMatch(string, query) {
        return string.toLowerCase().indexOf(query.toLowerCase()) !== -1;
    }


    /**
     * Recursively filters a tree showing only the nodes
     * that match the query and its children.
     * @param data The tree
     * @param query The query
     * @returns array
     */
    filter(data, query) {
        return data.reduce((acc, node) => {
            if (this.isAMatch(node.name, query)) {
                acc.push(node)
            } else if (node.children && node.children.length) {
                const validNodes = this.filter(node.children, query)

                if (validNodes.length) {
                    const {children, ...newNode} = node

                    newNode.children = validNodes
                    acc.push(newNode)
                }
            }

            return acc

        }, [])


    }

    /**
     * Listener for the onChange event of the search field.
     *
     * It takes care of filtering the tree.
     *
     * @param e
     */
    handleSearch(e) {
        const query = e.target.value,
            data = query.trim() ? this.filter(this.state.originalData, query) : this.state.originalData;
        this.setState({query, data})
    }

    /**
     * Listener for the keyPress event in the document.
     *
     * Hides the modal if opened.
     *
     * @param event
     */
    escFunction(event) {
        if (event.keyCode === 27) {
            this.hideModal()
        }
    }

    getAppendable() {
        switch (this.props.mode) {
            case 'tagging':
                return this.renderRadioButton;
            case 'select':
                return this.renderCheckbox;
            default:
                return this.renderEditButton;
        }
    }

    /**
     * Removes the event listener for the escape key when unmounting the component.
     */
    componentWillUnmount() {
        document.removeEventListener("keydown", this.escFunction, false);
    }

    render() {
        return (
            <React.Fragment>
                <div
                    className={`box ${styles.hierarchySelector} ${this.props.mode === 'tagging' ? 'selector-in-modal' : ''}`}>
                    <div className={styles.header}>
                        <p className={styles.heading}>{Lang.trans('hierarchy_selector.hierarchy_selector')}</p>

                        <p className="control has-icons-left">
                            <input className="input is-small" placeholder={Lang.trans('general.search')} type="text"
                                   value={this.state.query}
                                   onChange={this.handleSearch}/>
                            <span className="icon is-small is-left">
                            <i className="fas fa-search" aria-hidden="true"/>
                        </span>
                        </p>
                    </div>
                    <div>
                        <TreeView data={this.state.data} appendList={this.renderAddButton}
                                  appendNode={this.getAppendable()}/>
                    </div>
                    {this.renderSelectButton()}
                </div>
                {this.renderModal()}
            </React.Fragment>
        )
    }

    renderError() {
        if (!this.state.error)
            return;

        return (<span className="has-text-danger">{this.state.error}</span>);
    }

    renderSelectButton() {
        if (this.props.mode !== 'tagging') return;

        return (
            <div>
                <button className={styles.selectionButton} onClick={() => this.props.onSelection(this.state.selection)}>{Lang.trans('general.select')}</button>
            </div>
        )
    }

    renderModal() {
        return (
            <div className={this.state.creating || this.state.editing ? 'modal is-active' : 'modal'}>
                <div className="modal-background"/>
                <div className="modal-card">
                    <header className="modal-card-head">
                        <p className="modal-card-title">{this.state.creating ? Lang.trans('hierarchy_selector.add_modal_title') : Lang.trans('hierarchy_selector.edit_modal_title')}</p>
                        <button className="delete" aria-label="close" onClick={this.hideModal}/>
                    </header>
                    <section className="modal-card-body">
                        <input className="input" type="text" placeholder={Lang.trans('labels.name')}
                               value={this.state.name}
                               onChange={e => this.setState({name: e.target.value})}/>
                    </section>
                    <footer className="modal-card-foot">
                        <button
                            className={this.state.sending ? 'button is-success is-loading' : 'button is-success'}
                            onClick={this.state.creating ? this.create : this.edit}>{this.state.creating ? Lang.trans('hierarchy_selector.add_node') : Lang.trans('hierarchy_selector.edit_node')}
                        </button>
                        <button className="button" onClick={this.hideModal}>{Lang.trans('general.cancel')}</button>
                        {this.renderError()}
                    </footer>
                </div>
            </div>
        )
    }

    componentDidMount() {
        document.addEventListener("keydown", this.escFunction, false);

        const url = this.props.mode === 'select' && this.props.catalog
            ? '/async/catalogs/' + this.props.catalog + '/edit'
            : '/async/species/'

        axios.get(url)
            .then(r => {
                const data = r.data

                this.setState({data, originalData: data})
            })

    }

    renderAddButton(element) {
        if (this.props.mode !== 'index')
            return;

        return (<AddButton element={element} onCreating={this.onCreating}/>)

    }

    renderEditButton(element) {
        if (this.props.mode !== 'index')
            return;
        return (<EditButton element={element} onEditing={this.onEditing}/>)

    }

    renderCheckbox(element) {
        if (this.props.mode !== 'select')
            return;

        return (<Checkbox element={element}/>)
    }

    handleRadioSelection(selection) {
        this.setState({selection})
    }

    renderRadioButton(element) {
        if (this.props.mode !== 'tagging')
            return;

        const active = this.state.selection && this.state.selection.id === element.id
            && this.state.selection.type === element.type;

        return (<RadioButton element={element} active={active} handleChange={this.handleRadioSelection}/>)
    }
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


/**
 * The RadioButton component.
 */
class RadioButton extends Component {

    constructor(props) {
        super(props)

    }

    render() {
        return (
            <>
                {this.props.active && (
                    <input type="hidden" name="taggable_type" value={this.props.element.type}/>
                )}
                <input type="radio" className="radio" onChange={() => this.props.handleChange(this.props.element)}
                       name="taggable_id" value={this.props.element.id}/>
            </>
        )
    }
}


const el = document.getElementById('hierarchy_selector');
if (el) {
    ReactDOM.render(<HierarchySelector mode={el.dataset.mode} catalog={el.dataset.catalog}/>, el);
}
