import React from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBox.scss'
import BoundingBoxOptions from './BoundingBoxOptions';
import Tippy from '@tippyjs/react';
import EditableArea from '../Areas/EditableArea';
import BoxerModes from '../BoxerModes';
import {focusBox} from '../store/actions/boxes';
import {useDispatch, useSelector} from 'react-redux';

export default function BoundingBox({box}) {

    const mode = useSelector(s => s.mode);
    const dispatch = useDispatch();

    const getBoundingBoxStyle = () => {
        return {
            width: box.width + 'px',
            height: box.height + 'px',
            top: box.top + 'px',
            left: box.left + 'px',
        }
    }

    const toggleFocus = () => {
        dispatch(focusBox(box.id, !box.focused));
    }

    const className = () => {
        let className = styles.boundingBox;

        if (box.highlighted)
            className += ' ' + styles.highlightedBox;

        if (mode === BoxerModes.EDIT)
            className += ' ' + styles.hoverable;

        if (box.editing)
            className += ' ' + styles.resizing;

        return className;
    }

    const boxOptions = (<BoundingBoxOptions box={box}/>);


    return (
        <>
            {box.editing && mode === BoxerModes.EDIT && (<EditableArea box={box}/>)}
            <Tippy content={boxOptions} visible={(box.focused || false) && mode === BoxerModes.EDIT}
                   appendTo={document.body} animation="fade" interactive={true} arrow={true}>
                <div className={className()} style={getBoundingBoxStyle()} onClick={() => toggleFocus()}/>
            </Tippy>
        </>
    )

}
