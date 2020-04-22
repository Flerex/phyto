import React, {Component, useEffect, useLayoutEffect, useState} from 'react'
import ReactDOM from 'react-dom';
import styles from '../../../sass/components/Boxer/Boxer.scss'
import SelectableArea from './SelectableArea'
import ZoomableArea from './ZoomableArea'
import {Button, Icon} from 'react-bulma-components'
import BoundingBox from './BoundingBox';
import BoundingBoxList from './BoundingBoxList';

// Constants
const CANVAS_SIZE = {
    width: 768,
    height: 600,
};

const INITIAL_MODE = 'zoom';

const DEFAULT_ZOOM = {
    scale: 1,
    position: {
        top: 0,
        left: 0,
    },
};


export default function Boxer(props) {
    // Refs
    const tagger = React.createRef();

    // State
    const [taggerDimensions, setTaggerDimensions] = useState(null);
    const [boxes, setBoxes] = useState(props.boxes.map(b => Object.assign(b, {persisted: true})));
    const [highlightedBox, setHighlightedBox] = useState(null);
    const [mode, setMode] = useState(INITIAL_MODE);
    const [zoom, setZoom] = useState(DEFAULT_ZOOM);

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

    useLayoutEffect(_ => {
        getImageSize(props.image).then(res => {
            const {x, y} = tagger.current.getBoundingClientRect();
            setTaggerDimensions(Object.assign({x, y}, res));
        })
    }, []);

    const onTarget = (box, visible) => {
        console.log(box)
    }

    const getCanvasStyle = () => ({
        width: CANVAS_SIZE.width,
        height: CANVAS_SIZE.height,
    })


    const createBoundingBox = (e, coords) => {

        if (coords.width <= 5 || coords.height <= 5) return;

        const alreadyExists = boxes.find(e => e.top === coords.top && e.left === coords.left
            && e.width === coords.width && e.height === coords.height);

        if (alreadyExists) return;


        const bb = {
            persisted: false,
            top: coords.top,
            left: coords.left,
            width: coords.width,
            height: coords.height,
            user: props.user,
            status: 'added',
        };

        setBoxes(boxes.concat(bb));
    }

    useEffect(_ => {
        // When boxes change, we look if there are changes that need to be persisted.
        boxes.filter(box => !box.persisted).forEach(bb => {

            if (bb.status === 'added') {
                axios.post(route('async.bounding_boxes.store', {image: props.imageKey}), bb)
                    .then(({data}) => {
                        persistBoundingBox(data);
                    });
            }

            if (bb.status === 'updated') {
                axios.post(route('async.bounding_boxes.update', {boundingBox: bb.id}), {
                    ...bb,
                    _method: 'PATCH'
                }).then(({data}) => {
                    const {top, left, width, height} = data;
                    persistBoundingBox({id: bb.id, top, left, width, height});
                });
            }

        })
    }, [boxes])

    const persistBoundingBox = ({id, top, left, width, height}) => {

        setBoxes(boxes
            .filter(el => !(el.width === width && el.height === height && el.top === top && el.left === left))
            .concat({id, top, left, width, height, persisted: true, user: props.user}));
    }

    const unhighlightBox = () => {
        setHighlightedBox(null)
    }

    const updateScale = absoluteValue => {
        setZoom({...zoom, scale: Math.max(Math.min(2, absoluteValue), .125)});
    }

    const modifyScale = (value) => {
        updateScale(zoom.scale + value)
    }

    const getFitScale = () => {
        if (!taggerDimensions) return null;

        const greatestProperty = taggerDimensions.height > taggerDimensions.width ? 'height' : 'width';

        return CANVAS_SIZE[greatestProperty] / taggerDimensions[greatestProperty];
    }

    const setScaleToFit = () => {
        updateScale(getFitScale())
    }

    const moveTo = (x, y) => {
        const updatedZoom = {...zoom};

        updatedZoom.position.left = x;
        updatedZoom.position.top = y;

        setZoom(updatedZoom);
    }

    const modifyPosition = (x, y) => {
        const updatedZoom = {...zoom};

        updatedZoom.position.left += x;
        updatedZoom.position.top += y;

        setZoom(updatedZoom);
    }

    const zoomIn = () => {
        modifyScale(+.4)
    }

    const zooming = (mode, delta) => {
        modifyScale(delta * -0.01)
    }

    const moving = (movementX, movementY) => {
        moveTo(zoom.position.left + movementX, zoom.position.top + movementY)
    }

    const updateBox = (id, newBox) => {
        setBoxes(boxes.filter(el => !(el.id === id)).concat({
            id, ...newBox,
            persisted: false,
            status: 'updated',
            user: props.user
        }));
    }

    const deleteBox = id => {
        setBoxes(boxes.filter(el => !(el.id === id)));
    }

    const handleRemove = id => {
        axios.post(route('async.bounding_boxes.destroy', {boundingBox: id}), {_method: 'DELETE'})
            .then(_ => {
                deleteBox(id)
            })
    }

    const renderBoundingBoxes = () => {
        return boxes.map((box, i) => (
            <BoundingBox key={i} highlighted={box.id === highlightedBox} box={box}
                         handleRemove={handleRemove} onClick={onTarget}
                         editable={mode === 'edit'} updateBox={(newBox) => updateBox(box.id, newBox)}/>
        ))
    }

    const renderToolbox = () => (
        <div className={styles.toolbox} style={{height: '45px'}}>
            {/* Zoom buttons */}
            <Button.Group className={styles.buttonGroup} hasAddons={true}>
                <Button rounded={true} onClick={() => modifyScale(-.1)} size="small" className={styles.button}
                        title={Lang.trans('boxer.zoom_out')}>
                    <Icon><i className="fas fa-search-minus"/></Icon>
                </Button>
                <Button rounded={true} onClick={() => modifyScale(+.1)} size="small" className={styles.button}
                        title={Lang.trans('boxer.zoom_in')}>
                    <Icon><i className="fas fa-search-plus"/></Icon>
                </Button>
            </Button.Group>

            {/* Move buttons */}
            <Button.Group className={styles.buttonGroup} hasAddons={true}>
                <Button rounded={true} onClick={() => modifyPosition(10, 0)} size="small"
                        className={styles.button}
                        title={Lang.trans('boxer.left')}>
                    <Icon><i className="fas fa-arrow-left"/></Icon>
                </Button>

                <Button rounded={true} onClick={() => modifyPosition(0, 10)} size="small"
                        className={styles.button}
                        title={Lang.trans('boxer.up')}>
                    <Icon><i className="fas fa-arrow-up"/></Icon>
                </Button>

                <Button rounded={true} onClick={() => modifyPosition(0, -10)} size="small"
                        className={styles.button}
                        title={Lang.trans('boxer.down')}>
                    <Icon><i className="fas fa-arrow-down"/></Icon>
                </Button>

                <Button rounded={true} onClick={() => modifyPosition(-10, 0)} size="small"
                        className={styles.button}
                        title={Lang.trans('boxer.right')}>
                    <Icon><i className="fas fa-arrow-right"/></Icon>
                </Button>

            </Button.Group>


            {/* Sizing buttons */}
            <Button.Group className={styles.buttonGroup} hasAddons={true}>
                <Button rounded={true} onClick={() => setScaleToFit()} size="small" className={styles.button}
                        disabled={getFitScale() === zoom.scale}
                        title={Lang.trans('boxer.scale_fit')}>
                    <Icon><i className="fas fa-compress-alt"/></Icon>
                </Button>

                <Button rounded={true} onClick={() => updateScale(1)} size="small" className={styles.button}
                        disabled={zoom.scale === 1} title={Lang.trans('boxer.scale_expand')}>
                    <Icon><i className="fas fa-expand-alt"/></Icon>
                </Button>
            </Button.Group>

            <Button rounded={true} onClick={() => moveTo(0, 0)} size="small" className={styles.button}
                    disabled={zoom.position.left === 0 && zoom.position.top === 0}
                    title={Lang.trans('boxer.restore_position')}>
                <Icon><i className="fas fa-crosshairs"/></Icon>
            </Button>

            {/* Mode switcher */}
            <Button.Group className={styles.buttonGroup} hasAddons={true} style={{marginLeft: 'auto'}}>
                <Button rounded={true} onClick={() => setMode('draw')} size="small" className={styles.button}
                        color={mode === 'draw' ? 'link' : null} title={Lang.trans('boxer.draw_mode')}>
                    <Icon><i className="fas fa-expand"/></Icon>
                </Button>
                <Button rounded={true} onClick={() => setMode('edit')} size="small" className={styles.button}
                        color={mode === 'edit' ? 'link' : null} title={Lang.trans('boxer.edit_mode')}>
                    <Icon><i className="fas fa-pen"/></Icon>
                </Button>
                <Button rounded={true} onClick={() => setMode('zoom')} size="small" className={styles.button}
                        color={mode === 'zoom' ? 'link' : null} title={Lang.trans('boxer.zoom_mode')}>
                    <Icon><i className="fas fa-mouse-pointer"/></Icon>
                </Button>
            </Button.Group>
        </div>
    )

    const getTaggerStyle = () => ({
        transform: 'scale(' + zoom.scale + ')',
        marginTop: zoom.position.top + 'px',
        marginLeft: zoom.position.left + 'px',
        width: taggerDimensions ? taggerDimensions.width : null,
        height: taggerDimensions ? taggerDimensions.height : null,
        backgroundImage: 'url(' + props.image + ')',
    })

    const renderModeArea = () => (
        <>
            <SelectableArea onMouseUp={createBoundingBox} disabled={mode !== 'draw'}/>
            <ZoomableArea onZoomIn={zoomIn} onZooming={zooming} onMoving={moving}
                          disabled={mode !== 'zoom'}/>
        </>)

    const renderCanvas = () => (
        <div className={styles.canvas} style={getCanvasStyle()}>
            <div className={styles.tagger} ref={tagger} style={getTaggerStyle()}>
                {renderModeArea()}
                {renderBoundingBoxes()}
            </div>
        </div>
    )


    return (
        <div className={styles.wrapper} style={{height: CANVAS_SIZE.height + 45 + 'px'}}>
            <div>
                {renderCanvas()}
                {renderToolbox()}
            </div>
            <BoundingBoxList highlighted={2} boxes={[...boxes]} highlightBox={setHighlightedBox}
                             unhighlightBox={unhighlightBox}/>
        </div>

    )

}

const el = document.getElementById('boxer');
if (el) {
    ReactDOM.render(<Boxer image={el.dataset.image} imageKey={el.dataset.imageKey}
                           createBbLink={el.dataset.createBbLink} user={el.dataset.user}
                           boxes={JSON.parse(el.dataset.boxes)}/>, el);
}


