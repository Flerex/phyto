import React, {useEffect, useState} from 'react'
import ReactDOM from 'react-dom';
import Tippy from '@tippyjs/react';
import styles from '../../../../../sass/components/Taxonomy/TaxonomyEditor.scss';

/**
 * The AddButton component.
 */
export default function AddButton({parent, onCreate}) {

    const portals = document.getElementById('portals')

    const [adding, setAdding] = useState(false)

    const [name, setName] = useState('') // The controlled input

    const [sending, setSending] = useState(false) // When AJAX request is being sent.

    const [error, setError] = useState(null) // Error returned by the AJAX request.

    const toggleCreating = _ => {
        setAdding(!adding)
        setError(null)
        setSending(false)
        setName('')
    }

    const renderError = () => {
        if (!error) return;

        return (<span className="has-text-danger">{error}</span>);
    };

    /**
     * Initiate the add node request.
     *
     * When called, uses the state data to send a request and create a new node.
     *
     * The needed state data is:
     *  - The parent node (so that when can get the id of it)
     *  - The name of the new node
     *  - The type of the node to be created
     */
    const add = () => {
        setSending(true)

        const request = {name, type: 'domain'}

        if (parent) {
            request.type = parent.contains
            request.parent = parent.id
        }

        axios.post(route('async.add_to_hierarchy'), request).then(({data}) => {
            if (!data.success) {
                setError(data.message)
                return
            }

            onCreate(parent?.children, {
                id: data.data.id,
                name,
                children: [],
            })

            setAdding(false)

        }).catch(e => {
            setError(e)
        }).finally(_ => {
            setSending(false)
        })
    };

    /**
     * Listener for the keyPress event in the document.
     *
     * Hides the modal if opened.
     *
     * @param event
     */
    const escFunction = (event) => {
        if (event.keyCode === 27) setAdding(false)
    }


    useEffect(() => {
        document.addEventListener("keydown", escFunction, false)

        return function cleanup() {
            document.removeEventListener("keydown", escFunction, false);
        }
    }, [])

    const renderAddModal = () => {
        if (!adding) return;

        return ReactDOM.createPortal((
            <div className="modal is-active">
                <div className="modal-background" onClick={toggleCreating}/>
                <div className="modal-card">
                    <header className="modal-card-head">
                        <p className="modal-card-title">{Lang.trans('taxonomy.add_modal_title')}</p>
                        <button className="delete" onClick={toggleCreating}/>
                    </header>
                    <section className="modal-card-body">
                        <input className="input" type="text" placeholder={Lang.trans('labels.name')}
                               value={name} onChange={e => setName(e.target.value)}/>
                    </section>
                    <footer className="modal-card-foot has-text-right">
                        <button className={sending ? 'button is-success is-loading' : 'button is-success'}
                                onClick={add}>
                            {Lang.trans('taxonomy.add_node')}
                        </button>
                        <button className="button" onClick={toggleCreating}>{Lang.trans('general.cancel')}</button>
                        {renderError()}
                    </footer>
                </div>
            </div>
        ), portals);
    };

    {
        return (
            <>
                <li className={styles.addNew} onClick={toggleCreating}>
                    <div>
                        <span className="icon"><i className="fas fa-plus-circle"/></span>
                        <span>{Lang.trans('taxonomy.add_new')}</span>
                    </div>
                </li>
                {renderAddModal()}
            </>
        )
    }
}
