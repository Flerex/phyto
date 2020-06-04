import React from 'react'
import styles from '../../../../sass/components/Boxer/Toolbox.scss';
import {Button, Icon} from 'react-bulma-components';
import {moveTo} from '../store/actions/zoom';
import {useDispatch, useSelector} from 'react-redux';

export default function MovementTools() {

    const zoom = useSelector(s => s.zoom);
    const dispatch = useDispatch();

    const modifyPosition = (x, y) => {
        dispatch(moveTo(zoom.position.top + y, zoom.position.left + x));
    }


    return (
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

    )
}
