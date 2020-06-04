import React, {useState} from 'react'
import styles from '../../../../sass/components/Boxer/EditableArea.scss'
import {camelCase} from 'camel-case'
import {setEditingBox} from '../store/actions/boxes'
import {useDispatch} from 'react-redux'

export default function EditableArea({box}) {

    // Constants
    const minimumSize = 5

    // Redux
    const dispatch = useDispatch()

    // State
    const [mode, setMode] = useState(null)
    const [selectionBoxOrigin, setSelectionBoxOrigin] = useState([box.left, box.top])
    const [selectionBoxTarget, setSelectionBoxTarget] = useState([+box.left + +box.width, +box.top + +box.height])

    const relativeCoordinates = {
        left: Math.abs(selectionBoxOrigin[0]),
        top: Math.abs(selectionBoxOrigin[1]),
        height: Math.abs(selectionBoxTarget[1] - selectionBoxOrigin[1]),
        width: Math.abs(selectionBoxTarget[0] - selectionBoxOrigin[0]),
    }

    const endDrag = () => {
        setMode(null)
        dispatch(setEditingBox(box.id, true, relativeCoordinates))
    }

    const startEditing = (y, x) => {
        setMode({y, x})
    }


    /*
     * We define all these functions inside an object manageResizing because:
     *
     * 1) We need to dynamically access them with a string (We need to do manageResizing[functionToCall]() because
     * there is no way to access the current scope without using eval(), which of course is evil)
     *
     * 2) They are related anyway.
     */
    const manageResizing = {}
    manageResizing.bottomRight = (newX, newY) => {

        if (newX < (selectionBoxOrigin[0] - minimumSize)
            || newY < (selectionBoxOrigin[1] - minimumSize))
            return;

        setSelectionBoxTarget([newX, newY])

    }

    manageResizing.bottomLeft = (newX, newY) => {

        if (newX > (selectionBoxTarget[0] - minimumSize)
            || newY < (selectionBoxOrigin[1] - minimumSize))
            return;

        setSelectionBoxTarget([selectionBoxTarget[0], newY])
        setSelectionBoxOrigin([newX, selectionBoxOrigin[1]])


    }

    manageResizing.topRight = (newX, newY) => {
        if (newX < (selectionBoxOrigin[0] - minimumSize)
            || newY > (selectionBoxTarget[1] - minimumSize))
            return;

        setSelectionBoxTarget([newX, selectionBoxTarget[1]])
        setSelectionBoxOrigin([selectionBoxOrigin[0], newY])
    }

    manageResizing.topLeft = (newX, newY) => {
        if (newX > (selectionBoxTarget[0] - minimumSize)
            || newY > (selectionBoxTarget[1] - minimumSize))
            return;

        setSelectionBoxOrigin([newX, newY])
    }

    const dragging = e => {
        if (!mode) return

        const functionName = camelCase(mode.y + '.' + mode.x)

        // Call the corresponding manageResizing.xY event handler
        manageResizing[functionName](e.nativeEvent.offsetX, e.nativeEvent.offsetY)
    }

    const containerStyle = {
        top: 0,
        left: 0,
        bottom: 0,
        right: 0,
        position: 'absolute',
    }


    const wrapperClassname = `${styles.wrapper} ${mode !== null ? styles.dragging : ''}`;
    return (
        <div className={wrapperClassname} style={containerStyle} onMouseMove={dragging} onMouseUp={endDrag}>
            <div className={styles.selection}
                 style={relativeCoordinates}>
                <div
                    className={`${styles.resizer} ${styles.top} ${styles.left}`}
                    onMouseDown={() => startEditing('top', 'left')}/>
                <div
                    className={`${styles.resizer} ${styles.bottom} ${styles.left}`}
                    onMouseDown={() => startEditing('bottom', 'left')}/>
                <div
                    className={`${styles.resizer} ${styles.top} ${styles.right}`}
                    onMouseDown={() => startEditing('top', 'right')}/>
                <div
                    className={`${styles.resizer} ${styles.bottom} ${styles.right}`}
                    onMouseDown={() => startEditing('bottom', 'right')}/>
            </div>
        </div>
    );

}
