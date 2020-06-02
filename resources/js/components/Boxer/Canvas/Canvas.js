import React, {useLayoutEffect} from 'react';
import styles from '../../../../sass/components/Boxer/Canvas.scss';
import SelectableArea from '../Areas/SelectableArea';
import ZoomableArea from '../Areas/ZoomableArea';
import BoundingBox from './BoundingBox';
import {updateDimensions} from '../store/actions/boxerDimensions';
import {useDispatch, useSelector} from 'react-redux';

const getImageSize = url => {
    return new Promise(resolve => {
        const img = new Image();
        img.onload = function () {
            resolve({
                width: this.width,
                height: this.height,
            });
        };
        img.src = url;
    })
}

export default function Canvas() {


    const boxerDimensions = useSelector(s => s.boxerDimensions);
    const zoom = useSelector(s => s.zoom);
    const image = useSelector(s => s.image);
    const canvas = useSelector(s => s.canvas);
    const boxes = useSelector(s => s.boxes);
    const dispatch = useDispatch();

    // Refs
    const imageContainer = React.createRef();

    // Styles
    const imageContainerStyle = {
        transform: 'scale(' + zoom.scale + ')',
        marginTop: zoom.position.top + 'px',
        marginLeft: zoom.position.left + 'px',
        width: boxerDimensions ? boxerDimensions.width : null,
        height: boxerDimensions ? boxerDimensions.height : null,
        backgroundImage: 'url(' + image.url + ')',
    }

    useLayoutEffect(_ => {
        getImageSize(image.url).then((res) => {
            const {x, y} = imageContainer.current.getBoundingClientRect();
            dispatch(updateDimensions({...res, x, y}))
        })
    }, []);


    const renderModeArea = () => (
        <>
            <SelectableArea/>
            <ZoomableArea/>
        </>
    );

    const renderBoundingBoxes = () => boxes.map((box, i) => (
        <BoundingBox key={i} box={box}/>
    ))


    const canvasStyle = {
        width: canvas.width + 'px',
        height: canvas.height + 'px',
    }

    return (
        <div className={styles.canvas} style={canvasStyle}>
            <div className={styles.tagger} ref={imageContainer} style={imageContainerStyle}>
                {renderModeArea()}
                {renderBoundingBoxes()}
            </div>
        </div>
    )
}
