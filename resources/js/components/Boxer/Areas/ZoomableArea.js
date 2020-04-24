import React, {useState} from 'react';
import connect from 'react-redux/lib/connect/connect';
import {addToScale, moveTo, moveToRelatively} from '../store/actions/zoom';
import BoxerModes from '../BoxerModes';

function ZoomableArea({dispatch, zoom, mode}) {

    // Only enable the component when we're in zoom mode.
    if (mode !== BoxerModes.ZOOM) return null;

    // State
    const [dragging, setDragging] = useState(false);


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

const mapStateToProps = state => ({
    zoom: state.zoom,
    mode: state.mode,
})

export default connect(mapStateToProps)(ZoomableArea);
