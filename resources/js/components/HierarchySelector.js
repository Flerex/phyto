import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import TreeView from './TreeView'
import styles from '../../sass/components/HierarchySelector.scss'

export default class HierarchySelector extends Component {


    constructor(props) {
        super(props)

        this.escFunction = this.escFunction.bind(this)
        this.renderAddButton = this.renderAddButton.bind(this)
        this.renderError = this.renderError.bind(this)
        this.hideModal = this.hideModal.bind(this)
        this.create = this.create.bind(this)
        this.onCreating = this.onCreating.bind(this)

        this.state = {
            data: [],
            originalData: [],
            query: '',
            name: '',

        }
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

        const parent = this.state.parent

        axios.post('/async/hierarchy/add', {
            type: parent.contains,
            parent: parent.id,
            name: this.state.name,
        }).then(({data}) => {

            if (!data.success) {
                this.setState({
                    error: data.message,
                    sending: false,
                })

                return

            }


            parent.children.push({
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


    /**
     * Hides the modal
     * @param e
     */
    hideModal(e) {
        this.setState({
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

    /**
     * Removes the event listener for the escape key when unmounting the component.
     */
    componentWillUnmount() {
        document.removeEventListener("keydown", this.escFunction, false);
    }

    render() {
        return (
            <React.Fragment>
                <div className={styles.hierarchySelector}>
                    <p className="panel-heading">{this.props.lang.title}</p>
                    <div className="panel-block">
                        <p className="control has-icons-left">
                            <input className="input is-small" placeholder={this.props.lang.search} type="text"
                                   value={this.state.query}
                                   onChange={this.handleSearch.bind(this)}/>
                            <span className="icon is-small is-left">
                            <i className="fas fa-search" aria-hidden="true"></i>
                        </span>
                        </p>
                    </div>
                    <TreeView data={this.state.data} appendList={this.renderAddButton}/>
                </div>
                {this.renderAddModal()}
            </React.Fragment>
        )
    }

    renderError() {
        if (!this.state.error)
            return;

        return (<span className="has-text-danger">{this.state.error}</span>);
    }

    renderAddModal() {
        return (
            <div className={this.state.creating ? 'modal is-active' : 'modal'}>
                <div className="modal-background"/>
                <div className="modal-card">
                    <header className="modal-card-head">
                        <p className="modal-card-title">{this.props.lang.add_modal_title}</p>
                        <button className="delete" aria-label="close" onClick={this.hideModal}/>
                    </header>
                    <section className="modal-card-body">
                        <input className="input" type="text" placeholder={this.props.lang.name}
                               onChange={e => this.setState({name: e.target.value})}/>
                    </section>
                    <footer className="modal-card-foot">
                        <button
                            className={this.state.sending ? 'button is-success is-loading' : 'button is-success'}
                            onClick={this.create}>{this.props.lang.add_node}
                        </button>
                        <button className="button" onClick={this.hideModal}>{this.props.lang.cancel}</button>
                        {this.renderError()}
                    </footer>
                </div>
            </div>
        )
    }

    componentDidMount() {
        document.addEventListener("keydown", this.escFunction, false);
        axios.get('/async/species')
            .then(r => {

                const data = r.data;

                this.setState({data, originalData: data})
            })

    }

    renderAddButton(element) {

        return (<AddButton element={element} lang={this.props.lang} onCreating={this.onCreating}/>)

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
        return (<li className={styles.add_new} onClick={this.clicked}>
            <span className="icon"><i className="fas fa-plus"/></span>
            <span>{this.props.lang.add_new}</span>
        </li>)
    }
}


const el = document.getElementById('hierarchy_selector');
if (el) {
    ReactDOM.render(<HierarchySelector lang={JSON.parse(el.dataset.lang)}/>, el);
}
