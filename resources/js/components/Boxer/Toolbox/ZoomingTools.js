import React from 'react'
import connect from 'react-redux/lib/connect/connect';
import styles from '../../../../sass/components/Boxer/Toolbox.scss';
import {Button, Icon} from 'react-bulma-components';
import {addToScale} from '../store/actions/zoom';

function ZoomingTools({dispatch}) {

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

export default connect()(ZoomingTools);
