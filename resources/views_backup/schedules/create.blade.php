@extends('layouts.app')

@section('content')

    <div class="wrapper">
        <h1>Add Schedule</h1>

        <div class="section content-area">
            <form>
                <div class="input-area">
                    <label for="select">Client Company</label>
                    <select name="select" required id="select">
                        <option>Select One</option>
                    </select>
                </div>
                <div class="input-area">
                    <label for="Project Name">Industry</label>
                    <select name="Project Name" id="Project Name">
                    </select>
                </div>
                <div class="input-area">
                    <label for="Client Name">Client Contact Person</label>
                    <input type="text"/>
                </div>
                <div class="input-area">
                    <label for="Client Name">Client Designation</label>
                    <input type="text"/>
                </div>
                <div class="input-area">
                    <div class="five columns">
                        <label for="date">Client Contact Details</label>
                    </div>
                    <div class="four columns">
                        <input type="email" class="input-icon icon-email">
                    </div>
                    <div class="three columns">
                        <input type="text" class="input-icon icon-phone"/>
                    </div>
                    <div class="section"><a href="#" class="fr">+ Add New Contact Person</a></div>
                </div>
                <div class="input-area">
                    <label for="Location">Remarks</label>
                    <textarea></textarea>
                </div>
                <div class="clearfix"></div>
                <input type="submit" class="fr sbtn" value="Create New Schedule">
                <input type="submit" class="fr sbtn gray" value="Edit">
            </form>
        </div>
    </div>


@endsection