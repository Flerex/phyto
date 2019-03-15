import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import TreeView from './TreeView'
import styles from '../../sass/components/HierarchySelector.scss'

export default class HierarchySelector extends Component {


    constructor(props) {
        super(props)

        this.renderAddButton = this.renderAddButton.bind(this)
        this.hideModal = this.hideModal.bind(this)
        this.create = this.create.bind(this)
        this.speciesName = this.speciesName.bind(this)
        this.onCreating = this.onCreating.bind(this)

        this.state = {
            data: [],
            originalData: [],
            query: '',

        }
    }

    isAMatch(string, query) {
        return string.toLowerCase().indexOf(query.toLowerCase()) !== -1;
    }

    create(e) {
        this.setState({
            sending: true,
        })

        const parent = this.state.parent

        axios.post('/async/hierarchy/add', {
            type: parent.contains,
            parent: parent.id,
            name: this.state.name,
        }).then(({data}) => {

            parent.children.push({
                id: data.data.id,
                name: this.state.name,
                children: [],
            })

            this.setState({
                creating: false,
            })

        }).catch(e => {
            alert(e)
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

    speciesName(e) {
        this.setState({
            name: e.target.value,
        })
    }


    hideModal(e) {
        this.setState({
            creating: false,
        })
    }


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

    handleSearch(e) {
        const query = e.target.value,
            data = query.trim() ? this.filter(this.state.originalData, query) : this.state.originalData;
        this.setState({query, data})
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
                        <input className="input" type="text" placeholder={this.props.lang.name} onChange={this.speciesName}/>
                    </section>
                    <footer className="modal-card-foot">
                        <button
                            className={this.state.sending ? 'button is-success is-loading' : 'button is-success'}
                            onClick={this.create}>{this.props.lang.add_node}
                        </button>
                        <button className="button" onClick={this.hideModal}>{this.props.lang.cancel}</button>
                    </footer>
                </div>
            </div>
        )
    }

    componentDidMount() {
        axios.get('/async/species')
            .then(r => {

                const data = r.data;

                data.forEach(c => {

                    c.type = 'domain'
                    Object.defineProperty(c, 'children', Object.getOwnPropertyDescriptor(c, 'classis'))
                    c.contains = 'classis'
                    delete c['classis']

                    c.children.forEach(c => {
                        c.type = 'classis'
                        Object.defineProperty(c, 'children', Object.getOwnPropertyDescriptor(c, 'genera'))
                        c.contains = 'genera'
                        delete c['genera']

                        c.children.forEach(c => {
                            c.type = 'genus'
                            Object.defineProperty(c, 'children', Object.getOwnPropertyDescriptor(c, 'species'))
                            c.contains = 'species'
                            delete c['species']

                            c.children.forEach(c => {
                                c.type = 'species'
                            })
                        })
                    })

                })


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
