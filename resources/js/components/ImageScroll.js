import React, {useEffect, useRef, useState} from 'react';
import ReactDOM from 'react-dom';
import styles from '../../sass/components/ImageScroll.scss'


export default function ImageScroll({images}) {

    const scroll = useRef(React.createRef())
    const active = useRef(React.createRef())

    const [isLeft, setIsLeft] = useState(false)
    const [isRight, setIsRight] = useState(false)

    useEffect(() => {
        const item = active.current;
        scroll.current.scrollLeft = item.offsetLeft - 2 - (scroll.current.offsetWidth / 2 - item.offsetWidth / 2)

        scrolling()
    }, [])


    const scrolling = _ => {
        setIsLeft(scroll.current.scrollLeft > 0)
        setIsRight(scroll.current.scrollLeft < scroll.current.scrollLeftMax)
    }

    const paginationClass = `${styles.pagination} ${isLeft ? styles.left : ''} ${isRight ? styles.right : ''}`

    return (
        <div className={paginationClass}>
            <div ref={scroll} className={styles.scroll} onScroll={scrolling}>
                {images.map((img, key) => (
                    <a key={key} ref={img.active ? active : null}
                       className={`${styles.item} ${img.active ? styles.active : ''}`} href={img.href}>
                        <img src={img.thumbnail_link}/>
                    </a>
                ))}
            </div>
        </div>
    );
}

document.querySelectorAll('.image-scroll').forEach(el => {
    ReactDOM.render(<ImageScroll images={JSON.parse(el.dataset.images)}/>, el);
})
