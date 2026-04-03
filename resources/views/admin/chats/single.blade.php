@extends('layouts.admin')

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المحادثات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض المحادثة</li>
    @endsection

@section('content')
@section('title', $pageTitle)
@section('pageName', $pageTitle)
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css') }}" />
@endpush
@push('scripts')
<script src="{{ asset('assets/js/app-chat.js')}}"></script>
@endpush

<div class="app-chat card overflow-hidden" id="ChatAppPage" chat-id="{{ $id }}">
    <div class="row g-0">
      <!-- Sidebar Left -->
      <div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left">
        <div
          class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
          <div class="avatar avatar-xl avatar-online">
            <img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
          </div>
          <h5 class="mt-2 mb-0">John Doe</h5>
          <span>Admin</span>
          <i
            class="ti ti-x ti-sm cursor-pointer close-sidebar"
            data-bs-toggle="sidebar"
            data-overlay
            data-target="#app-chat-sidebar-left"></i>
        </div>
        <div class="sidebar-body px-4 pb-4">
          <div class="my-4">
            <small class="text-muted text-uppercase">About</small>
            <textarea
              id="chat-sidebar-left-user-about"
              class="form-control chat-sidebar-left-user-about mt-3"
              rows="4"
              maxlength="120">
Dessert chocolate cake lemon drops jujubes. Biscuit cupcake ice cream bear claw brownie brownie marshmallow.</textarea
            >
          </div>
          <div class="my-4">
            <small class="text-muted text-uppercase">Status</small>
            <div class="d-grid gap-2 mt-3">
              <div class="form-check form-check-success">
                <input
                  name="chat-user-status"
                  class="form-check-input"
                  type="radio"
                  value="active"
                  id="user-active"
                  checked />
                <label class="form-check-label" for="user-active">Active</label>
              </div>
              <div class="form-check form-check-danger">
                <input
                  name="chat-user-status"
                  class="form-check-input"
                  type="radio"
                  value="busy"
                  id="user-busy" />
                <label class="form-check-label" for="user-busy">Busy</label>
              </div>
              <div class="form-check form-check-warning">
                <input
                  name="chat-user-status"
                  class="form-check-input"
                  type="radio"
                  value="away"
                  id="user-away" />
                <label class="form-check-label" for="user-away">Away</label>
              </div>
              <div class="form-check form-check-secondary">
                <input
                  name="chat-user-status"
                  class="form-check-input"
                  type="radio"
                  value="offline"
                  id="user-offline" />
                <label class="form-check-label" for="user-offline">Offline</label>
              </div>
            </div>
          </div>
          <div class="my-4">
            <small class="text-muted text-uppercase">Settings</small>
            <ul class="list-unstyled d-grid gap-2 me-3 mt-3">
              <li class="d-flex justify-content-between align-items-center">
                <div>
                  <i class="ti ti-message me-1 ti-sm"></i>
                  <span class="align-middle">Two-step Verification</span>
                </div>
                <label class="switch switch-primary me-4 switch-sm">
                  <input type="checkbox" class="switch-input" checked="" />
                  <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                  </span>
                </label>
              </li>
              <li class="d-flex justify-content-between align-items-center">
                <div>
                  <i class="ti ti-bell me-1 ti-sm"></i>
                  <span class="align-middle">Notification</span>
                </div>
                <label class="switch switch-primary me-4 switch-sm">
                  <input type="checkbox" class="switch-input" />
                  <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                  </span>
                </label>
              </li>
              <li>
                <i class="ti ti-user-plus me-1 ti-sm"></i>
                <span class="align-middle">Invite Friends</span>
              </li>
              <li>
                <i class="ti ti-trash me-1 ti-sm"></i>
                <span class="align-middle">Delete Account</span>
              </li>
            </ul>
          </div>
          <div class="d-flex mt-4">
            <button
              class="btn btn-primary"
              data-bs-toggle="sidebar"
              data-overlay
              data-target="#app-chat-sidebar-left">
              Logout
            </button>
          </div>
        </div>
      </div>
      <!-- /Sidebar Left-->

      <!-- Chat & Contacts -->
            <!-- /Chat contacts -->

      <!-- Chat History -->
      <div class="col app-chat-history bg-body">
        <div class="chat-history-wrapper">
          <div class="chat-history-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
              <div class="d-flex overflow-hidden align-items-center">
                <i
                  class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2"
                  data-bs-toggle="sidebar"
                  data-overlay
                  data-target="#app-chat-contacts"></i>
                <div class="flex-shrink-0 avatar">
                  <img
                    src="../../assets/img/avatars/2.png"
                    alt="Avatar"
                    class="rounded-circle"
                    data-bs-toggle="sidebar"
                    data-overlay
                    data-target="#app-chat-sidebar-right" />
                </div>
                <div class="chat-contact-info flex-grow-1 ms-2">
                  <h6 class="m-0">Felecia Rower</h6>
                  <small class="user-status text-muted">NextJS developer</small>
                </div>
              </div>
              
            </div>
          </div>
          <div class="chat-history-body bg-body">
            <ul class="list-unstyled chat-history">
              <li class="chat-message chat-message-right">
                <div class="d-flex overflow-hidden">
                  <div class="chat-message-wrapper flex-grow-1">
                    <div class="chat-message-text">
                      <p class="mb-0">How can we help? We're here for you! 😄</p>
                    </div>
                    <div class="text-end text-muted mt-1">
                      <i class="ti ti-checks ti-xs me-1 text-success"></i>
                      <small>10:00 AM</small>
                    </div>
                  </div>
                  <div class="user-avatar flex-shrink-0 ms-3">
                    <div class="avatar avatar-sm">
                      <img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                    </div>
                  </div>
                </div>
              </li>
              <li class="chat-message">
                <div class="d-flex overflow-hidden">
                  <div class="user-avatar flex-shrink-0 me-3">
                    <div class="avatar avatar-sm">
                      <img src="../../assets/img/avatars/2.png" alt="Avatar" class="rounded-circle" />
                    </div>
                  </div>
                  <div class="chat-message-wrapper flex-grow-1">
                    <div class="chat-message-text">
                      <p class="mb-0">Hey John, I am looking for the best admin template.</p>
                      <p class="mb-0">Could you please help me to find it out? 🤔</p>
                    </div>
                    <div class="chat-message-text mt-2">
                      <p class="mb-0">It should be Bootstrap 5 compatible.</p>
                    </div>
                    <div class="text-muted mt-1">
                      <small>10:02 AM</small>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        
        </div>
      </div>
      <!-- /Chat History -->

      <!-- Sidebar Right -->
      <div class="col app-chat-sidebar-right app-sidebar overflow-hidden" id="app-chat-sidebar-right">
        <div
          class="sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
          <div class="avatar avatar-xl avatar-online">
            <img src="../../assets/img/avatars/2.png" alt="Avatar" class="rounded-circle" />
          </div>
          <h6 class="mt-2 mb-0">Felecia Rower</h6>
          <span>NextJS Developer</span>
          <i
            class="ti ti-x ti-sm cursor-pointer close-sidebar d-block"
            data-bs-toggle="sidebar"
            data-overlay
            data-target="#app-chat-sidebar-right"></i>
        </div>
        <div class="sidebar-body px-4 pb-4">
          <div class="my-4">
            <small class="text-muted text-uppercase">About</small>
            <p class="mb-0 mt-3">
              A Next. js developer is a software developer who uses the Next. js framework alongside ReactJS
              to build web applications.
            </p>
          </div>
          <div class="my-4">
            <small class="text-muted text-uppercase">Personal Information</small>
            <ul class="list-unstyled d-grid gap-2 mt-3">
              <li class="d-flex align-items-center">
                <i class="ti ti-mail ti-sm"></i>
                <span class="align-middle ms-2">josephGreen@email.com</span>
              </li>
              <li class="d-flex align-items-center">
                <i class="ti ti-phone-call ti-sm"></i>
                <span class="align-middle ms-2">+1(123) 456 - 7890</span>
              </li>
              <li class="d-flex align-items-center">
                <i class="ti ti-clock ti-sm"></i>
                <span class="align-middle ms-2">Mon - Fri 10AM - 8PM</span>
              </li>
            </ul>
          </div>
          <div class="mt-4">
            <small class="text-muted text-uppercase">Options</small>
            <ul class="list-unstyled d-grid gap-2 mt-3">
              <li class="cursor-pointer d-flex align-items-center">
                <i class="ti ti-badge ti-sm"></i>
                <span class="align-middle ms-2">Add Tag</span>
              </li>
              <li class="cursor-pointer d-flex align-items-center">
                <i class="ti ti-star ti-sm"></i>
                <span class="align-middle ms-2">Important Contact</span>
              </li>
              <li class="cursor-pointer d-flex align-items-center">
                <i class="ti ti-photo ti-sm"></i>
                <span class="align-middle ms-2">Shared Media</span>
              </li>
              <li class="cursor-pointer d-flex align-items-center">
                <i class="ti ti-trash ti-sm"></i>
                <span class="align-middle ms-2">Delete Contact</span>
              </li>
              <li class="cursor-pointer d-flex align-items-center">
                <i class="ti ti-ban ti-sm"></i>
                <span class="align-middle ms-2">Block Contact</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- /Sidebar Right -->

      <div class="app-overlay"></div>
    </div>
  </div>
@endsection
