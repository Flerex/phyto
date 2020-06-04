import React from 'react'
import styles from '../../../../sass/components/Boxer/Toolbox.scss';
import {Button, Icon} from 'react-bulma-components';
import {addToScale} from '../store/actions/zoom';
import {useDispatch} from 'react-redux';

export default function ZoomingTools() {

    const dispatch = useDispatch();

    const modifyScale = scale => {
        dispatch(addToScale(scale));
    }

    return (
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
    )
}
