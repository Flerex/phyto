import React, {useState} from 'react';
import {addToScale, moveToRelatively} from '../store/actions/zoom';
import BoxerModes from '../BoxerModes';
import {useDispatch, useSelector} from 'react-redux';

export default function ZoomableArea() {

    const mode = useSelector(s => s.mode);
    const dispatch = useDispatch();

    // State
    const [dragging, setDragging] = useState(false);

    // Only enable the component when we're in zoom mode.
    if (mode !== BoxerModes.ZOOM) return null;

    // Styles
    const containerStyle = {
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        position: 'absolute',
    }

    const zoomIn = () => {
        dispatch(addToScale(.4))
    }

    const zooming = (e) => {
        if (e.deltaY === 0) return;
        e.preventDefault();
        dispatch(addToScale(e.deltaY * -.01))
    }

    const draggingEvent = e => {
        if (!dragging) return;
        dispatch(moveToRelatively(e.movementY, e.movementX));

    }


    return (
        <div style={containerStyle} onDoubleClick={zoomIn} onMouseMove={draggingEvent} onWheel={zooming}
             onMouseLeave={() => setDragging(false)} onMouseDown={() => setDragging(true)}
             onMouseUp={() => setDragging(false)}/>
    );

}
