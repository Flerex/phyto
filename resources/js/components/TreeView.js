import React, {Component} from 'react'

export default class TreeView extends Component {


    constructor(props) {
        super(props)

        this.renderAppendList = this.renderAppendList.bind(this)
    }

    renderAppendList() {
        if (!this.props.appendList)
            return

        return this.props.appendList(null)
    }

    render() {
        return (
            <div>
                <ul>
                    {this.props.data.map((el, i) => <Node key={i} element={el}
                                                          renderAppendList={this.renderAppendList}
                                                          appendNode={this.props.appendNode}/>)}
                    {this.renderAppendList()}
                </ul>
            </div>
        )
    }


}


/**
 * Component representing a node of the tree.
 *
 * This component will also render the node's children recursively.
 */
class Node extends Component {

    constructor(props) {
        super(props)

        this.hasChildren = this.hasChildren.bind(this)
        this.toggle = this.toggle.bind(this)
        this.renderAppendNode = this.renderAppendNode.bind(this)
        this.renderChildren = this.renderChildren.bind(this)

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
                <div onClick={this.toggle}>
                    <span className="icon" hidden={!this.hasChildren()}><i
                        className={this.state.expanded ? 'fas fa-chevron-down' : 'fas fa-chevron-right'}/></span>
                    <span className={this.hasChildren() ? 'has-text-weight-bold' : ''}>{el.name}</span>
                    {this.renderAppendNode()}
                </div>
                {this.renderChildren()}
            </li>
        )
    }


    hasChildren() {
        return this.props.element.hasOwnProperty('children') && this.props.element.children.length > 0;
    }


    renderChildren() {

        if (!this.hasChildren())
            return '';

        return (
            <ul hidden={!this.state.expanded}>
                {this.props.element.children.map((el, i) =>
                    <Node key={i} element={el} renderAppendList={this.props.renderAppendList} appendNode={this.props.appendNode}/>
                )}
                {this.props.renderAppendList()}
            </ul>
        )
    }

    renderAppendNode() {
        if (!this.props.appendNode)
            return

        return this.props.appendNode(this.props.element)
    }
}

