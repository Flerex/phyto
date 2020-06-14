import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import Select from 'react-select';


const VitaminedSelector = ({isMulti, name, old, options}) => (
    <Select isMulti={isMulti} alwaysOpen={isMulti} name={name} defaultValue={old}
                 options={options}/>
)

export default VitaminedSelector


document.querySelectorAll('.vitamined-selector').forEach(el => {
    const old = el.dataset.old ? JSON.parse(el.dataset.old) : null;
    ReactDOM.render(<VitaminedSelector isMulti={!!el.dataset.isMulti} old={old} name={el.dataset.name} options={JSON.parse(el.dataset.options)}/>, el)
})
