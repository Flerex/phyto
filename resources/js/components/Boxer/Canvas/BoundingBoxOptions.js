import React from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBoxOptions.scss'
import {Button, Icon} from 'react-bulma-components'
import {deleteBox, editBox, setEditingBox, setTaggingBox} from '../store/actions/boxes';
import {useDispatch} from 'react-redux';

export default function BoundingBoxOptions({box}) {

    const dispatch = useDispatch();

    const toggleEditMode = () => {
        dispatch(setEditingBox(box.id, !box.editing));
    }

    const saveResizing = () => {
        dispatch(editBox(box.id, {...box.temporalCoordinates}));
        dispatch(setEditingBox(box.id, false));
    }

    const setTagMode = () => {
        dispatch(setTaggingBox(box.id, !box.tagging));
    }

    const removeBox = () => {
        dispatch(deleteBox(box.id));
    }

    const renderDefaultButtons = () => {
        if (box.editing) return;

        return (
            <>
                <Button onClick={setTagMode} color="black" size="small" className={styles.button}>
                    <Icon><i className="fas fa-tag"/></Icon>
                </Button>

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

    if (!box.persisted) return (
        <span className="icon"><i className="fas fa-spinner fa-pulse"/></span>
    );

    return (<>
        {renderDefaultButtons()}
        {renderResizingButtons()}
    </>)

}
