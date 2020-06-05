import React from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBox.scss'
import BoundingBoxOptions from './BoundingBoxOptions';
import Tippy from '@tippyjs/react';
import EditableArea from '../Areas/EditableArea';
import BoxerModes from '../BoxerModes';
import {focusBox, setTaggingBox} from '../store/actions/boxes';
import {useDispatch, useSelector} from 'react-redux';
import ReactDOM from 'react-dom';
import Tagger from '../Tagger/Tagger';

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

        if (box.taggable)
            className += ' ' + styles.tagged;

        return className;
    }

    const boxOptions = (<BoundingBoxOptions box={box}/>);

    const renderModes = () => {
        if (mode !== BoxerModes.EDIT) return // We need to be in the editing mode to show any of this.

        return (
            <>
                {box.editing && (<EditableArea box={box}/>)}
                {box.tagging && (<Tagger box={box} />)}
            </>
        );
    }

    return (
        <>
            {renderModes()}
            <Tippy content={boxOptions} visible={(box.focused || false) && mode === BoxerModes.EDIT}
                   appendTo={document.body} animation="fade" interactive={true} arrow={true}>
                <div className={className()} style={getBoundingBoxStyle()} onClick={() => toggleFocus()}/>
            </Tippy>
        </>
    )

}
