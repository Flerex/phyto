import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import AsyncSelect from 'react-select/async'


export default class MemberSelector extends Component {


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

        this.promiseOptions = this.promiseOptions.bind(this)
    }

    componentDidMount() {
        const ids = this.props.old ? this.props.old.map(i => parseInt(i)) : null
        const enabled = true
        const project = this.props.project

        if (ids && ids.length)
            axios.get(route('async.search_members', {project}), {params: {ids}})
                .then(({data}) => this.setState({data, enabled}));
        else
            this.setState({enabled})

    }

    promiseOptions(query) {
        const project = this.props.project;
        return axios.get(route('async.search_members', {project}), {params: {query}}).then(r => r.data)
    }


    render() {
        if (!this.state.enabled) return null;

        return (
            <AsyncSelect isMulti cacheOptions alwaysOpen name="users[]" defaultValue={this.state.data}
                         loadOptions={this.promiseOptions} defaultOptions={true}/>
        )
    }

}


const el = document.getElementById('member_selector')
if (el) {
    const old = el.dataset.old ? JSON.parse(el.dataset.old) : null;
    ReactDOM.render(<MemberSelector old={old} project={el.dataset.project}/>, el)
}
