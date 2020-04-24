import React from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBoxOptions.scss'
import {Button, Icon} from 'react-bulma-components'
import connect from 'react-redux/lib/connect/connect';
import {deleteBox, editBox, persistBox, setEditingBox} from '../store/actions/boxes';

function BoundingBoxOptions({box, dispatch}) {

    const toggleEditMode = () => {
        dispatch(setEditingBox(box.id, !box.editing));
    }

    const saveResizing = () => {
        dispatch(editBox(box.id, {...box.temporalCoordinates}));
        axios.post(route('async.bounding_boxes.update', {boundingBox: box.id}), {
            ...box.temporalCoordinates,
            _method: 'PATCH'
        }).then(({data: {id}}) => {
            dispatch(persistBox(id));
        });
        dispatch(setEditingBox(box.id, false));
    }

    const removeBox = () => {
        dispatch(deleteBox(box.id));
        axios.post(route('async.bounding_boxes.destroy', {boundingBox: box.id}), {_method: 'DELETE'});
    }

    const renderDefaultButtons = () => {
        if (box.editing) return;

        return (
            <>
                <Button onClick={toggleEditMode} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-expand-arrows-alt"/></Icon>
                </Button>

                <Button onClick={removeBox} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-trash-alt"/></Icon>
                </Button>
            </>
        )
    }

    const renderResizingButtons = () => {
        if (!box.editing) return;

        return (
            <>
                <Button onClick={saveResizing} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-save"/></Icon>
                </Button>
                <Button onClick={toggleEditMode} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-times"/></Icon>
                </Button>
            </>
        )
    }

    if (!box.persisted) return null;

    return (<>
        {renderDefaultButtons()}
        {renderResizingButtons()}
    </>)

}

export default connect()(BoundingBoxOptions);
