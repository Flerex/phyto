import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import TreeView from './TreeView'
import styles from '../../sass/components/HierarchySelector.scss'
import AsyncSelect from 'react-select/lib/Async';


export default class UserSelector extends Component {


    constructor(props) {
        super(props)
    }

    promiseOptions(query) {
        return axios.get('/async/users/search', { params: {query} }).then(r => r.data)
    }


    render() {
        return (
            <AsyncSelect isMulti cacheOptions alwaysOpen name="users[]" loadOptions={this.promiseOptions}/>
        )
    }

}


const el = document.getElementById('user_selector')
if (el) {
    ReactDOM.render(<UserSelector />, el)
}
