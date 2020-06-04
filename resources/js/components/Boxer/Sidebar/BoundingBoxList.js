import React from 'react'
import styles from '../../../../sass/components/Boxer/BoundingBoxList.scss'
import {Heading} from 'react-bulma-components';
import BoundingBoxListItem from './BoundingBoxListItem';
import {useSelector} from 'react-redux';

export default function BoundingBoxList() {

    const boxes = useSelector(s => s.boxes);

    const {untagged, tagged} = boxes.reduce((acc, item) => {
        acc[item.taggable ? 'tagged' : 'untagged'].push(item)
        return acc;
    }, {untagged: [], tagged: []});

    const renderUntaggedBoxes = () => {
        if (!untagged.length) return

        return (
            <>
                <Heading className={styles.label}>{Lang.trans('boxer.untagged')}</Heading>
                {untagged.map((box, i) => (<BoundingBoxListItem key={i} box={box}/>))}
            </>
        )
    }

    const renderTaggedBoxes = () => {
        if (!tagged.length) return

        return (
            <>
                <Heading className={styles.label}>{Lang.trans('boxer.box_list')}</Heading>
                {tagged.map((box, i) => (<BoundingBoxListItem key={i} box={box}/>))}
            </>
        )
    }

    const renderEmptyWarning = () => {
        if (boxes.length) return;
        return (<span className="has-text-grey">{Lang.trans('boxer.no_boxes')}</span>)
    };

    return (
        <div className={styles.bbList}>
            {renderEmptyWarning()}
            {renderUntaggedBoxes()}
            {renderTaggedBoxes()}
        </div>
    )
}
