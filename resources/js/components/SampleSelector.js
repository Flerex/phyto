import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import AsyncSelect from 'react-select/async'


export default class SampleSelector extends Component {


    constructor(props) {
        super(props)


        /*
         * We need to set up a helper state attribute `enabled` so that the AsyncSelect component
         * is never rendered until we know the old() default values via the asynchronoust request.
         *
         * If loaded earlier, t
         * hose values won't show because the property would change after it
         * was rendered.
         */
        this.state = {
            enabled: false,
            data: [],
        };

        this.promiseOptions = this.promiseOptions.bind(this)
    }

    componentDidMount() {
        const ids = this.props.old ? [this.props.old] : null
        const enabled = true
        const project = this.props.project

        if (ids)
            axios.get(route('async.search_samples', {project}), {params: {ids}})
                .then(({data}) => this.setState({data, enabled}));
        else
            this.setState({enabled})

    }

    promiseOptions(query) {
        const project = this.props.project
        return axios.get(route('async.search_samples', {project}), {params: {query}}).then(r => r.data)
    }


    render() {
        if (!this.state.enabled) return null;

        return (
            <AsyncSelect cacheOptions alwaysOpen name="sample" defaultValue={this.state.data}
                         loadOptions={this.promiseOptions} defaultOptions={true}/>
        )
    }

}


const el = document.getElementById('sample_selector')
if (el) {
    ReactDOM.render(<SampleSelector old={el.dataset.old} project={el.dataset.project}/>, el)
}
