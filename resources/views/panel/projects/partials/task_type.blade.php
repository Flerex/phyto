@if($task->automated)
    <span class="task-type icon task-type__automated" data-tippy-content="@lang('labels.task.automated')">
        <i class="fas fa-robot"></i>
    </span>
@else
    <span class="task-type icon" data-tippy-content="@lang('labels.task.manual')">
        <i class="fas fa-tasks"></i>
    </span>
@endif

