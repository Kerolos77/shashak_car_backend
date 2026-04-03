@extends('layouts.admin')

@section('title', __('Chats'))
@section('pageName', __('Chat Management'))

@section('breadcrumbs')
     
        <li class="breadcrumb-item text-muted"> المحادثات </li>
        <span class="bullet bg-gray-300 w-5px h-2px"></span>
        <li class="breadcrumb-item text-dark">عرض الكل</li>
    @endsection

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css') }}" />
        <style>
            .chat-contact-list {
                max-height: calc(100vh - 200px);
                overflow-y: auto;
            }
            .chat-contact-list-item {
                transition: all 0.3s ease;
                border-radius: 0.5rem;
                padding: 0.75rem 1rem;
            }
            .chat-contact-list-item:hover {
                background-color: rgba(var(--kt-primary-rgb), 0.05);
            }
            .chat-contact-list-item.active {
                background-color: rgba(var(--kt-primary-rgb), 0.1);
                border-left: 3px solid var(--kt-primary);
            }
            .chat-contact-avatar {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                object-fit: cover;
            }
            .unread-badge {
                width: 20px;
                height: 20px;
                font-size: 0.7rem;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .last-message-time {
                font-size: 0.75rem;
            }
            .chat-search {
                border-radius: 0.475rem;
                padding: 0.65rem 1rem;
            }
            .chat-contact-name {
                font-size: 0.95rem;
                font-weight: 500;
            }
            .chat-contact-status {
                font-size: 0.825rem;
                color: var(--kt-gray-600);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/app-chat.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize perfect scrollbar
                new KTScroll(document.getElementById('chat-list'), {
                    wheelSpeed: 0.5,
                    suppressScrollX: true
                });

                // Highlight active chat
                const currentChatId = {{ request()->route('id') ?? 'null' }};
                if (currentChatId) {
                    document.querySelector(`a[href*="${currentChatId}"]`).closest('.chat-contact-list-item').classList.add('active');
                }
            });
        </script>
    @endpush

    <div class="card">
        <!-- Header -->
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">{{ __('Chat Conversations') }}</h3>
            </div>
            <div class="card-toolbar">
                <div class="d-flex align-items-center position-relative me-4">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
                        </svg>
                    </span>
                    <input type="text" class="form-control form-control-solid chat-search ps-12" placeholder="{{ __('Search chats...') }}">
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body p-0">
            <div class="app-chat overflow-hidden">
                <ul class="list-unstyled chat-contact-list py-2 mb-0" id="chat-list">
                    @forelse ($rooms as $room)
                        <li class="chat-contact-list-item">
                            <a class="d-flex align-items-center px-6 py-4" href="{{ route('admin.chats.single', $room->id) }}">
                                <div class="position-relative">
                                    <img src="{{ $room->trip?->user?->avatar_url ?? asset('assets/media/avatars/blank.png') }}" 
                                         class="chat-contact-avatar" 
                                         alt="{{ $room->trip?->user?->full_name ?? 'Unknown User' }}">
                                    @if($room->unread_count > 0)
                                        <span class="unread-badge badge bg-primary rounded-circle position-absolute top-0 end-0">
                                            {{ $room->unread_count }}
                                        </span>
                                    @endif
                                </div>

                                <div class="chat-contact-info flex-grow-1 ms-4 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="chat-contact-name text-truncate m-0">
                                            {{ __('Client') }}: {{ $room->trip?->user?->full_name ?? 'Unknown User' }}
                                        </h6>
                                        @if($room->latest_message && $room->latest_message->isNotEmpty())
                                            <small class="text-muted last-message-time">
                                                {{ $room->latest_message->first()->created_at->diffForHumans() }}
                                            </small>
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="chat-contact-status text-truncate text-muted">
                                            {{ __('Driver') }}: {{ $room->trip?->driver?->full_name ?? 'Unknown Driver' }}
                                        </small>
                                        @if($room->latest_message && $room->latest_message->isNotEmpty())
                                            <small class="last-message-preview text-muted text-truncate ms-2">
                                                {{ Str::limit($room->latest_message->first()->message, 30) }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="text-center py-8">
                            <div class="symbol symbol-100px symbol-circle mb-5">
                                <div class="symbol-label bg-light-primary">
                                    <span class="svg-icon svg-icon-5x svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path opacity="0.3" d="M21 18H3C2.4 18 2 17.6 2 17V7C2 6.4 2.4 6 3 6H21C21.6 6 22 6.4 22 7V17C22 17.6 21.6 18 21 18Z" fill="currentColor"></path>
                                            <path d="M12 8C10.9 8 10 7.1 10 6C10 4.9 10.9 4 12 4C13.1 4 14 4.9 14 6C14 7.1 13.1 8 12 8ZM10 14C10 12.9 10.9 12 12 12C13.1 12 14 12.9 14 14C14 15.1 13.1 16 12 16C10.9 16 10 15.1 10 14ZM10 20C10 18.9 10.9 18 12 18C13.1 18 14 18.9 14 20C14 21.1 13.1 22 12 22C10.9 22 10 21.1 10 20Z" fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <h4 class="text-gray-600">{{ __('No chat conversations found') }}</h4>
                            <p class="text-muted">{{ __('When customers start conversations, they will appear here') }}</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Footer -->
        @if($rooms->hasPages())
            <div class="card-footer d-flex justify-content-end">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
@endsection