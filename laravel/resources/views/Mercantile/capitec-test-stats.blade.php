@extends('layouts.header') 

<x-app-layout>
    <x-slot name="header">
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4>Mercantile Records</h4>
                    <div class="row">
                        <div class="col-sm-6">
                            <h5>Capitec Test Records</h5>

                            <table class="table table-bordered">
                                <tr>
                                    <th>Policy Number</th>
                                    <th>Full Name</th>
                                    <th>Action Date</th>
                                </tr>
                                @foreach ($capitecQuery as $row)
                                <tr>
                                    <td>{{ $row->PolicyNumber }}</td>
                                    <td>{{ $row->AccountHolderFullName }}</td>
                                    <td>{{ $row->UserAccountNumber }}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                <div class="row">
                    {!! $capitecQuery->links() !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
