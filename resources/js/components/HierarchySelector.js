import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import TreeView from './TreeView'
import styles from '../../sass/components/HierarchySelector.scss'

export default class HierarchySelector extends Component {


    constructor(props) {
        super(props)

        this.state = {
            data: [],
            originalData: [],
            query: '',
        }
    }

    isAMatch(string, query) {
        return string.toLowerCase().indexOf(query.toLowerCase()) !== -1;
    }


    filter(data, query) {
        return data.filter(node => {

            if (this.isAMatch(node.name, query))
                return true;

            if (!node.children) {
                return false;
            }

            const filteredChildren = this.filter(node.children, query)

            node.children = filteredChildren

            if (filteredChildren.length) {
                return true
            }

        })
    }

    handleSearch(e) {
        const query = e.target.value,
            data = query.trim() ? this.filter(this.getCopyOfData(), query) : this.state.originalData;
        this.setState({query, data})
    }

    getCopyOfData() {
        return JSON.parse(JSON.stringify(this.state.originalData));
    }

    render() {
        return (
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
                <TreeView data={this.state.data}/>
            </div>
        )
    }

    componentDidMount() {
        axios.get('/async/species')
            .then(r => {

                const data = r.data;

                data.forEach(c => {

                    Object.defineProperty(c, 'children', Object.getOwnPropertyDescriptor(c, 'classis'))
                    delete c['classis']

                    c.children.forEach(c => {
                        Object.defineProperty(c, 'children', Object.getOwnPropertyDescriptor(c, 'genera'))
                        delete c['genera']

                        c.children.forEach(c => {
                            Object.defineProperty(c, 'children', Object.getOwnPropertyDescriptor(c, 'species'))
                            delete c['species']
                        })
                    })

                })


                this.setState({data, originalData: data})
            })

    }
}

const el = document.getElementById('hierarchy_selector');
if (el) {
    ReactDOM.render(<HierarchySelector lang={JSON.parse(el.dataset.lang)}/>, el);
}
