import React from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBoxList.scss'
import {Heading} from 'react-bulma-components';
import BoundingBoxListItem from './BoundingBoxListItem';
import {useSelector} from 'react-redux';

export default function BoundingBoxList() {

    const boxes = useSelector(s => s.boxes);

    const renderBoxes = () => {
        if (!boxes.length) {
            return (<span className="has-text-grey">{Lang.trans('boxer.no_boxes')}</span>)
        }
        return boxes.map((box, i) => (<BoundingBoxListItem key={i} box={box}/>));
    }

    return (
        <div className={styles.bbList}>
            <Heading className={styles.label}>{Lang.trans('boxer.box_list')}</Heading>
            {renderBoxes()}
        </div>
    )
}
