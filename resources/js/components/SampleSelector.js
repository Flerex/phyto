import React, {useState, useEffect} from 'react'
import ReactDOM from 'react-dom'
import AsyncSelect from 'react-select/async'
import Select from 'react-select'
import Tippy from '@tippyjs/react'
import styles from '../../sass/components/SampleSelector.scss'


export default function SampleSelector({project, old}) {

    /*
     * We need to set up a helper state attribute `enabled` so that the AsyncSelect component is never rendered
     * until we know the old() default values via the asynchronous request.
     *
     * If loaded earlier, those values won't show because the property would change after it was rendered.
     */

    const [enabled, setEnabled] = useState(false)
    const [data, setData] = useState([])

    const [loading, setLoading] = useState(false)

    const [currentSample, setCurrentSample] = useState(null)
    const [tasks, setTasks] = useState([])


    useEffect(() => {
        const ids = old ? [old] : null

        if (!ids) {
            setEnabled(true)
            return
        }

        axios.get(route('async.search_samples', {project}), {params: {ids}})
            .then(({data}) => {
                setData(data)
                setEnabled(true)
            })
    }, [])


    useEffect(() => {
        if (!currentSample) return;

        setLoading(true)

        const sample = currentSample.value

        axios.get(route('async.search_tasks', {project}), {params: {sample}}).then(({data}) => {
            setTasks(data)
            setLoading(false)
        })


    }, [currentSample])


    const promiseOptions = query => {
        return axios.get(route('async.search_samples', {project}), {params: {query}}).then(r => r.data)
    }

    const formatTaskOptionLabel = ({sample: {name}, date}) => (
        <div>
            <div>{name}</div>
            <div className={styles.sublabel}>
                {date}
            </div>
        </div>
    );

    const renderLoading = () => {
        if (!loading) return;

        return (<span className="icon"><i className="fas fa-spinner fa-pulse"/></span>)
    }

    const renderCompatibility = () => {

        if (!tasks.length) return;

        return (
            <>
                <div className="field">
                    <label className="label">
                        <span>{Lang.trans('panel.projects.tasks.compatibility')}</span>
                        <Tippy content={Lang.trans('panel.projects.tasks.compatibility_explained')}>
                            <span className="icon has-text-grey"><i className="fas fa-info-circle"/></span>
                        </Tippy>
                    </label>
                    <div className="control">
                        <Select isMulti alwaysOpen
                                options={tasks.map(task => ({...task, value: task.id}))}
                                formatOptionLabel={formatTaskOptionLabel}
                                name="compatibility[]"/>
                    </div>
                </div>
            </>
        )
    };

    if (!enabled) return null;


    return (
        <>
            <div className="field">
                <label
                    className="label">{Lang.trans_choice('panel.projects.samples.label', 1)} {renderLoading()}</label>
                <div className="control">
                    <AsyncSelect cacheOptions alwaysOpen name="sample" defaultValue={data}
                                 loadOptions={promiseOptions} onChange={setCurrentSample} defaultOptions={true}/>
                </div>
            </div>

            {renderCompatibility()}
        </>
    )

}


const el = document.getElementById('sample_selector')
if (el) {
    ReactDOM.render(<SampleSelector old={el.dataset.old} project={el.dataset.project}/>, el)
}
