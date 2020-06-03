import React, {useState} from 'react'
import styles from '../../../../sass/components/Boxer/Tagger.scss'
import {setTaggingBox} from '../store/actions/boxes';
import {useDispatch, useSelector} from 'react-redux';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import TaxonomyPicker from '../../Taxonomy/TaxonomyPicker';

export default function Tagger({box}) {

    const catalogs = useSelector(s => s.catalogs)
    const tree = useSelector(s => s.tree)
    const dispatch = useDispatch()

    const portals = document.getElementById('portals')

    const cancelTagging = () => {
        dispatch(setTaggingBox(box.id, false));
    }

    return ReactDOM.createPortal((
        <div className={`modal ${styles.modal}${box.tagging ? ' is-active' : ''}`}>
            <div className="modal-background" onClick={cancelTagging}/>
            <div className="modal-content">

                <TaxonomyPicker tree={tree} catalogs={catalogs}/>
            </div>
        </div>
    ), portals);

}
