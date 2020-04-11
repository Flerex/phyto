import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import AsyncSelect from 'react-select/lib/Async';


export default class UserSelector extends Component {


    constructor(props) {
        super(props)


        /*
         * We need to set up a helper state attribute `enabled` so that the AsyncSelect component
         * is never rendered until we know the old() default values via the asynchronoust request.
         *
         * If loaded earlier, those values won't show because the property would change after it
         * was rendered.
         */
        this.state = {
            enabled: false,
            data: [],
        };
    }

    componentDidMount() {

        const ids = this.props.old.map(i => parseInt(i)),
            enabled = true;

        if (ids.length)
            axios.get(route('async.search_users'), {params: {ids}})
                .then(({data}) => this.setState({data, enabled}));
        else
            this.setState({enabled})

    }

    promiseOptions(query) {
        return axios.get(route('async.search_users'), {params: {query}}).then(r => r.data)
    }


    render() {
        if (!this.state.enabled) return null;

        return (
            <AsyncSelect isMulti cacheOptions alwaysOpen name="users[]" defaultValue={this.state.data}
                         loadOptions={this.promiseOptions}/>
        )
    }

}


const el = document.getElementById('user_selector')
if (el) {
    ReactDOM.render(<UserSelector old={JSON.parse(el.dataset.old)}/>, el)
}
