import React, {useEffect, useState} from 'react'
import ReactDOM from 'react-dom';
import Tippy from '@tippyjs/react';
import styles from '../../../../../sass/components/Taxonomy/TaxonomyEditor.scss';

/**
 * The EditButton component.
 */
export default function EditButton({element, onUpdate}) {

    const portals = document.getElementById('portals')

    const [editing, setEditing] = useState(false)

    const [name, setName] = useState(element.name) // The controlled input


    const [sending, setSending] = useState(false) // When AJAX request is being sent.

    const [error, setError] = useState(null) // Error returned by the AJAX request.

    const toggleEditing = _ => {
        setEditing(!editing)
        setError(null)
        setSending(false)
        setName(element.name)
    }

    const renderError = () => {
        if (!error) return;

        return (<span className="has-text-danger">{error}</span>);
    };

    /**
     * Initiate the Edit node request.
     */
    const edit = () => {
        setSending(true)

        axios.post(route('async.edit_node'), {
            type: element.type,
            id: element.id,
            name,
        }).then(({data}) => {
            if (!data.success) {
                setError(data.message)
                return
            }

            onUpdate(element, data.data.name)

            setEditing(false)
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
        if (event.keyCode === 27) setEditing(false)
    }


    useEffect(() => {
        document.addEventListener("keydown", escFunction, false)

        return function cleanup() {
            document.removeEventListener("keydown", escFunction, false);
        }
    }, [])

    const renderEditModal = () => {
        if (!editing) return;

        return ReactDOM.createPortal((
            <div className="modal is-active">
                <div className="modal-background" onClick={toggleEditing}/>
                <div className="modal-card">
                    <header className="modal-card-head">
                        <p className="modal-card-title">{Lang.trans('taxonomy.edit_modal_title')}</p>
                        <button className="delete" onClick={toggleEditing}/>
                    </header>
                    <section className="modal-card-body">
                        <input className="input" type="text" placeholder={Lang.trans('labels.name')}
                               value={name} onChange={e => setName(e.target.value)} autoFocus={true}/>
                    </section>
                    <footer className="modal-card-foot has-text-right">
                        <button className={sending ? 'button is-success is-loading' : 'button is-success'}
                                onClick={edit}>
                            {Lang.trans('taxonomy.edit_node')}
                        </button>
                        <button className="button" onClick={toggleEditing}>{Lang.trans('general.cancel')}</button>
                        {renderError()}
                    </footer>
                </div>
            </div>
        ), portals);
    };

    {
        return (
            <>
                <Tippy content={Lang.trans('taxonomy.edit_node')}>
                    <button className={`button is-small is-light is-rounded is-link ${styles.editButton}`}
                            onClick={toggleEditing}>
                        <span className="icon"><i className="fas fa-edit"/></span>
                    </button>
                </Tippy>
                {renderEditModal()}
            </>
        )
    }
}
