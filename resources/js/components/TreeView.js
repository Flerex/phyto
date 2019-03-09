import React, {Component} from 'react';

export default class TreeView extends Component {


    constructor(props) {
        super(props)
    }

    render() {
        return (
            <div>
                <ul>
                    {this.props.data.map((el, i) => <Node key={i} element={el}/>)}
                </ul>
            </div>
        )
    }
}


class Node extends Component {

    constructor(props) {
        super(props)

        this.state = {
            expanded: true,
        };
    }

    toggle() {
        this.setState({
            expanded: !this.state.expanded,
        })
    }

    render() {
        const el = this.props.element;

        return (
            <li>
                <div onClick={this.toggle.bind(this)}>
                    <span className="icon" hidden={!this.hasChildren.call(this)}><i className={this.state.expanded ? 'fas fa-minus-square' : 'fas fa-plus-square'}></i></span>
                    {el.name}
                </div>
                {this.renderChildren()}
            </li>
        )
    }

    hasChildren() {
        return this.props.element.hasOwnProperty('children') && this.props.element.children.length > 0;
    }

    renderChildren() {

        if (!this.hasChildren.call(this))
            return '';

        return (
            <ul hidden={!this.state.expanded}>
                {this.props.element.children.map((el, i) =>
                    <Node key={i} element={el}/>
                )}
            </ul>
        )
    }
}
