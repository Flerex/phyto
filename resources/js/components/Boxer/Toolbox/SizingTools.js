import React from 'react'
import connect from 'react-redux/lib/connect/connect';
import styles from '../../../../sass/components/Boxer/Toolbox.scss';
import {Button, Icon} from 'react-bulma-components';
import {moveTo, setScale} from '../store/actions/zoom';

function SizingTools({zoom, taggerDimensions, canvas, dispatch}) {

    const updateScale = absoluteValue => {
        dispatch(setScale(absoluteValue))
    }

    const getFitScale = () => {
        if (!taggerDimensions) return null;

        const greatestProperty = taggerDimensions.height > taggerDimensions.width ? 'height' : 'width';

        return canvas[greatestProperty] / taggerDimensions[greatestProperty];
    }

    const setScaleToFit = () => {
        updateScale(getFitScale())
    }

    const overridePosition = (x, y) => {
        dispatch(moveTo(y, x));
    }


    return (
        <>
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
            <Button rounded={true} onClick={() => overridePosition(0, 0)} size="small" className={styles.button}
                    disabled={zoom.position.left === 0 && zoom.position.top === 0}
                    title={Lang.trans('boxer.restore_position')}>
                <Icon><i className="fas fa-crosshairs"/></Icon>
            </Button>
        </>
    )
}


const mapStateToProps = state => ({
    zoom: state.zoom,
    canvas: state.canvas,
    taggerDimensions: state.taggerDimensions,

})

export default connect(mapStateToProps)(SizingTools);
