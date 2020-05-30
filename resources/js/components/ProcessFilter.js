import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import Select from 'react-select';


export default class ProcessFilter extends Component {

    constructor(props) {
        super(props)

        this.change = this.change.bind(this)
        this.getDefaultValue = this.getDefaultValue.bind(this)
    }

    change(option) {
        if(option.value === this.props.old) return;

        if(option.value === null) {
            window.location.href = this.props.route
        } else {
            window.location.href = this.props.route + '?process=' + encodeURIComponent(option.value)
        }
    }

    getDefaultValue() {
        return this.props.processes.find(e => e.value === this.props.old)
    }

    render() {
        return (
            <Select options={this.props.processes} onChange={this.change} defaultValue={this.getDefaultValue()}/>
        )
    }

}


const el = document.getElementById('process_filter')
if (el) {
    ReactDOM.render(<ProcessFilter processes={JSON.parse(el.dataset.processes)} route={el.dataset.route}
                                   old={JSON.parse(el.dataset.old)}/>, el)
}
