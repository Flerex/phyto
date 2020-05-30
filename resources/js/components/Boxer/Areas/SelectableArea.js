import React, {useState} from 'react';
import styles from '../../../../sass/components/Boxer/SelectableArea.scss'
import {HotKeys} from 'react-hotkeys'
import connect from 'react-redux/lib/connect/connect';
import {addBox, persistBox} from '../store/actions/boxes';
import BoxerModes from '../BoxerModes';

function SelectableArea({boxes, mode, user, image, dispatch}) {

    let animationInProgress = null;

    // Constants

    const isDisabled = mode !== BoxerModes.DRAW;

    const keyboardShortcuts = {
        keymap: {
            CANCEL_SELECTION: 'esc',
        },
        handlers: {
            CANCEL_SELECTION: () => stopSelection(),
        }
    };


    // State
    const [hold, setHold] = useState(false);
    const [selectionBox, setSelectionBox] = useState(false);
    const [selectionBoxOrigin, setSelectionBoxOrigin] = useState([0, 0]);
    const [selectionBoxTarget, setSelectionBoxTarget] = useState([0, 0]);
    const [animation, setAnimation] = useState('');


    const createBoundingBox = coords => {

        if (coords.width <= 5 || coords.height <= 5) return;

        const alreadyExists = boxes.find(e => e.top === coords.top && e.left === coords.left
            && e.width === coords.width && e.height === coords.height);

        if (alreadyExists) return;

        dispatch(addBox(coords, user));

        axios.post(route('async.bounding_boxes.store', {image: image.key}), coords)
            .then(({data}) => {
                dispatch(persistBox(data));
            });
    }

    const endSelection = _ => {
        if (selectionBox) {
            createBoundingBox(getSelectionCoordinates());
        }
        stopSelection();
    }

    const stopSelectionAndAnimation = () => {
        setSelectionBox(false);
        setAnimation('');
    }

    const stopSelection = () => {
        setHold(false);
        setAnimation(styles.selectionFadeOut);


        animationInProgress = setTimeout(() => {

            stopSelectionAndAnimation();

            animationInProgress = null;

        }, 300);
    }

    const beginSelection = (e) => {

        if (isDisabled) return;

        clearTimeout(animationInProgress);
        animationInProgress = null;

        stopSelectionAndAnimation();

        if (animation.length > 0 && e.target.id === 'react-rectangle-selection') {
            stopSelectionAndAnimation();
        }

        setHold(true);
        setSelectionBoxOrigin([e.nativeEvent.offsetX, e.nativeEvent.offsetY]);
        setSelectionBoxTarget([e.nativeEvent.offsetX, e.nativeEvent.offsetY]);
    }

    const dragging = e => {
        if (hold && !selectionBox) {
            setSelectionBox(true);
        }

        if (selectionBox && !animationInProgress) {
            setSelectionBoxTarget([e.nativeEvent.offsetX, e.nativeEvent.offsetY]);
        }

    }


    const getRelativeCoordinates = {
        left: Math.abs(selectionBoxOrigin[0]),
        top: Math.abs(selectionBoxOrigin[1]),
        height: Math.abs(selectionBoxTarget[1] - selectionBoxOrigin[1]),
        width: Math.abs(selectionBoxTarget[0] - selectionBoxOrigin[0]),
    }


    /**
     * Obtain the CSS always valid properties for the given selection.
     *
     * This is needed because getRelativeCoordinates can be modified by a tansform if it was created
     * the other way around.
     *
     * @returns {{top: number, left: number, width: number, height: number} & {top: number, left: *}}
     */
    const getSelectionCoordinates = () => {

        const coordinates = getRelativeCoordinates;


        let top = coordinates.top,
            left = coordinates.left;

        if (selectionBoxOrigin[1] > selectionBoxTarget[1])
            top -= coordinates.height

        if (selectionBoxOrigin[0] > selectionBoxTarget[0])
            left -= coordinates.width

        return Object.assign(coordinates, {top, left})
    }

    const handleTransformBox = () => {

        if (selectionBoxOrigin[1] > selectionBoxTarget[1] &&
            selectionBoxOrigin[0] > selectionBoxTarget[0])
            return 'scaleY(-1) scaleX(-1)';

        if (selectionBoxOrigin[1] > selectionBoxTarget[1]) return 'scaleY(-1)';
        if (selectionBoxOrigin[0] > selectionBoxTarget[0]) return 'scaleX(-1)';

        return null;
    }

    const renderSelectionBox = () => {
        if (!selectionBox) return;

        const baseStyle = Object.assign({
            transform: handleTransformBox()
        }, getRelativeCoordinates);

        return (
            <div className={`${animation} ${styles.selection}`} id="react-rectangle-selection"
                 style={baseStyle}/>
        )
    }

    if (isDisabled) return null;

    return (
        <HotKeys keyMap={keyboardShortcuts.keymap} handlers={keyboardShortcuts.handlers}>
            <div className={styles.container} onMouseMove={dragging} onMouseUp={endSelection}
                 onMouseLeave={stopSelection} onMouseDown={beginSelection}>
                {renderSelectionBox()}
            </div>
        </HotKeys>
    );

}

const mapStateToProps = state => ({
    mode: state.mode,
    user: state.user,
    boxes: state.boxes,
    image: state.image,
})

export default connect(mapStateToProps)(SelectableArea);
