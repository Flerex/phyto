import React from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBoxListItem.scss'
import connect from 'react-redux/lib/connect/connect';
import {highlightBox} from '../store/actions/boxes';

function BoundingBoxListItem({box, dispatch}) {


    const highlight = highlighted => {
        dispatch(highlightBox(box, highlighted));
    }

    return (
        <div className={styles.boxInfo} onMouseEnter={() => highlight(true)} onMouseLeave={() => highlight(false)}>
            <div className={styles.boxIcon}><i className="fas fa-question"/></div>
            <div>
                <div>
                    <em>{Lang.trans('boxer.untagged')}</em>
                    {!box.persisted && (<i className={`fas fa-spinner fa-spin ${styles.uploading}`}/>)}
                </div>
                <div className={styles.author}>
                    {Lang.trans('boxer.by')} <strong>{box.user}</strong>
                </div>
            </div>
        </div>
    )
}

export default connect()(BoundingBoxListItem);
