@extends('templates.layout')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h1>Your Projects</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <project-status-panel></project-status-panel>
            <project-status-panel></project-status-panel>
            <project-status-panel></project-status-panel>
            <project-status-panel></project-status-panel>
        </div>
        <div class="row">
            <project-status-panel></project-status-panel>
            <project-status-panel></project-status-panel>
            <project-status-panel></project-status-panel>
            <project-status-panel></project-status-panel>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading panel-success">
                        <h1>Your Contributions</h1>
                    </div>
                    <user-contributions></user-contributions>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h1>Your Profile</h1>
                        </div>
                        <div class="panel-body">
                            <user-details></user-details>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop