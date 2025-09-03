@extends('layouts.app')

@section('title', 'Issue: ' . $issue->title)

@section('content')
<!-- Include Heroicons for icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@2.0.13/24/outline/index.min.css">

<div class="max-w-4xl mx-auto p-6 bg-gradient-to-br from-gray-900 to-gray-800 text-gray-100 rounded-2xl shadow-2xl">
    <!-- Issue Header -->
    <h1 class="text-4xl font-extrabold mb-6 text-center text-indigo-300 tracking-tight">{{ $issue->title }}</h1>

    <!-- Issue Details -->
    <div class="bg-gray-800/50 p-6 rounded-xl mb-8 shadow-inner border border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <p class="text-gray-300"><span class="font-semibold text-indigo-200">Project:</span> {{ $issue->project->name }}</p>
            <p class="text-gray-300"><span class="font-semibold text-indigo-200">Status:</span> {{ ucfirst(str_replace('_', ' ', $issue->status)) }}</p>
            <p class="text-gray-300"><span class="font-semibold text-indigo-200">Priority:</span> {{ ucfirst($issue->priority) }}</p>
            <p class="text-gray-300"><span class="font-semibold text-indigo-200">Due Date:</span> {{ $issue->due_date ?? '-' }}</p>
        </div>
        <p class="mt-4 text-gray-300"><span class="font-semibold text-indigo-200">Description:</span> {{ $issue->description }}</p>
    </div>

    <!-- Assigned Users Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-white">Assigned Members</h2>
            <button onclick="openUserModal()" class="flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Assign Member
            </button>
        </div>
        <ul id="users-list" class="flex flex-wrap gap-3">
            @foreach($issue->users as $user)
                <li id="user-{{ $user->id }}" class="flex items-center bg-gray-700 rounded-full px-4 py-2 shadow-sm transition duration-200 hover:bg-gray-600">
                    <span class="font-semibold text-sm text-white">{{ $user->name }} ({{ $user->email }})</span>
                    <button onclick="detachUser({{ $issue->id }}, {{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')" class="font-semibold text-sm ml-2" style="color: #FF0000" onmouseover="this.style.color='#CC0000'" onmouseout="this.style.color='#FF0000'">Remove</button>
                </li>
            @endforeach
        </ul>
        <div id="user-errors" class="text-red-400 mt-2 text-sm"></div>
    </div>

    <!-- Tags Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-white">Tags</h2>
            <button onclick="openTagModal()" class="flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Attach Tag
            </button>
        </div>
        <ul id="tags-list" class="flex flex-wrap gap-3">
            @foreach($issue->tags as $tag)
                <li id="tag-{{ $tag->id }}" class="flex items-center bg-gray-700 rounded-full px-4 py-2 shadow-sm transition duration-200 hover:bg-gray-600">
                    <span style="background: {{ $tag->color }}; padding: 2px 10px; border-radius: 12px; margin-right: 8px; font-size: 0.9rem;">{{ $tag->name }}</span>
                    <button onclick="detachTag({{ $issue->id }}, {{ $tag->id }}, '{{ $tag->name }}', '{{ $tag->color }}')" class="font-semibold text-sm" style="color: #FF0000" onmouseover="this.style.color='#CC0000'" onmouseout="this.style.color='#FF0000'">Remove</button>
                </li>
            @endforeach
        </ul>
        <div id="tag-errors" class="text-red-400 mt-2 text-sm"></div>
    </div>

    <!-- Comments Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-white">Comments</h2>
            <button onclick="openCommentModal()" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                Add Comment
            </button>
        </div>
        <div id="comments-list" class="space-y-4"></div>
        <div id="pagination" class="flex justify-center space-x-4 mt-6"></div>
        <div id="comment-errors" class="text-red-400 mt-2 text-sm"></div>
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-4 justify-center">
        <a href="{{ route('issues.index') }}" class="flex items-center bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Cancel
        </a>
        <a href="{{ route('issues.edit', $issue) }}" class="flex items-center bg-yellow-500 hover:bg-yellow-600 text-gray-800 font-semibold px-5 py-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit
        </a>
        <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button
                type="submit"
                class="flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                onclick="return confirm('Delete this issue?')"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4M9 7v12m6-12v12"></path></svg>
                Delete
            </button>
        </form>
    </div>
</div>

<!-- User Modal -->
<div id="user-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="p-4 rounded-2xl shadow-2xl max-w-xs w-[100%] transform scale-95 transition-transform duration-300 border border-gray-400" style="background-color: #D3D3D3">
        <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            Assign Member
        </h2>
        <select id="user-select" class="w-full p-2 mb-3 bg-gray-100 text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 text-sm">
            @foreach($users as $user)
                @if(!$issue->users->contains($user->id))
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endif
            @endforeach
        </select>
        <div class="flex justify-end space-x-2">
            <button onclick="closeUserModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-2 py-1 rounded-lg transition duration-200 text-sm">Cancel</button>
            <button id="attach-user-btn" onclick="attachUser({{ $issue->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded-lg transition duration-200 text-sm">Assign</button>
        </div>
        <div id="user-modal-errors" class="text-red-500 mt-2 text-xs"></div>
    </div>
</div>

<!-- Tag Modal -->
<div id="tag-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="p-4 rounded-2xl shadow-2xl max-w-xs w-[100%] transform scale-95 transition-transform duration-300 border border-gray-400" style="background-color: #D3D3D3">
        <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10m0 0v10m0-10l-7 7"></path></svg>
            Attach Tag
        </h2>
        <select id="tag-select" class="w-full p-2 mb-3 bg-gray-100 text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-200 text-sm">
            @foreach($tags as $tag)
                @if(!$issue->tags->contains($tag->id))
                    <option value="{{ $tag->id }}" data-color="{{ $tag->color }}">{{ $tag->name }}</option>
                @endif
            @endforeach
        </select>
        <div class="flex justify-end space-x-2">
            <button onclick="closeTagModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-2 py-1 rounded-lg transition duration-200 text-sm">Cancel</button>
            <button id="attach-tag-btn" onclick="attachTag({{ $issue->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-2 py-1 rounded-lg transition duration-200 text-sm">Attach</button>
        </div>
        <div id="tag-modal-errors" class="text-red-500 mt-2 text-xs"></div>
    </div>
</div>

<!-- Comment Modal -->
<div id="comment-modal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center hidden transition-opacity duration-300">
    <div class="p-4 rounded-2xl shadow-2xl max-w-xs w-[100%] transform scale-95 transition-transform duration-300 border border-gray-400" style="background-color: #D3D3D3">
        <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
            <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
            Add Comment
        </h2>
        <form id="comment-form">
            <label class="block mb-1 text-gray-800 font-semibold text-sm">Author Name</label>
            <input type="text" id="author_name" class="w-full p-2 mb-3 bg-gray-100 text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 text-sm" value="">
            <label class="block mb-1 text-gray-800 font-semibold text-sm">Body</label>
            <textarea id="body" class="w-full p-2 mb-3 bg-gray-100 text-gray-800 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 text-sm" rows="3"></textarea>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeCommentModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-2 py-1 rounded-lg transition duration-200 text-sm">Cancel</button>
                <button type="button" onclick="addComment({{ $issue->id }})" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded-lg transition duration-200 text-sm">Submit</button>
            </div>
        </form>
        <div id="comment-modal-errors" class="text-red-500 mt-2 text-xs"></div>
    </div>
</div>

<script>
    let currentPage = 1;
    loadComments({{ $issue->id }}, currentPage);

    function openUserModal() {
        const modal = document.getElementById('user-modal');
        const userSelect = document.getElementById('user-select');
        const attachButton = document.getElementById('attach-user-btn');
        const errorsDiv = document.getElementById('user-modal-errors');
        errorsDiv.innerHTML = '';

        if (userSelect.options.length === 0) {
            errorsDiv.innerHTML = '<p>No users available to assign.</p>';
            attachButton.disabled = true;
            attachButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            attachButton.disabled = false;
            attachButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('div').classList.remove('scale-95'), 0);
    }

    function closeUserModal() {
        const modal = document.getElementById('user-modal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('user-modal-errors').innerHTML = '';
        }, 300);
    }

    function openTagModal() {
        const modal = document.getElementById('tag-modal');
        const tagSelect = document.getElementById('tag-select');
        const attachButton = document.getElementById('attach-tag-btn');
        const errorsDiv = document.getElementById('tag-modal-errors');
        errorsDiv.innerHTML = '';

        if (tagSelect.options.length === 0) {
            errorsDiv.innerHTML = '<p>No tags available to attach.</p>';
            attachButton.disabled = true;
            attachButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            attachButton.disabled = false;
            attachButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('div').classList.remove('scale-95'), 0);
    }

    function closeTagModal() {
        const modal = document.getElementById('tag-modal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('tag-modal-errors').innerHTML = '';
        }, 300);
    }

    function openCommentModal() {
        const modal = document.getElementById('comment-modal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('div').classList.remove('scale-95'), 0);
    }

    function closeCommentModal() {
        const modal = document.getElementById('comment-modal');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('comment-modal-errors').innerHTML = '';
            document.getElementById('author_name').value = '';
            document.getElementById('body').value = '';
        }, 300);
    }

    function loadComments(issueId, page) {
        fetch(`/issues/${issueId}/comments?page=${page}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            const commentsList = document.getElementById('comments-list');
            commentsList.innerHTML = '';
            data.data.forEach(comment => {
                const div = document.createElement('div');
                div.className = 'bg-gray-800/50 p-5 rounded-xl shadow-md transition duration-200 hover:shadow-lg';
                div.innerHTML = `
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <p class="text-white font-semibold">${comment.author_name}</p>
                        <span class="text-gray-500 text-sm ml-2">(${comment.created_at})</span>
                    </div>
                    <p class="text-gray-300">${comment.body}</p>
                `;
                commentsList.appendChild(div);
            });

            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';
            if (data.prev_page_url) {
                const prevButton = document.createElement('button');
                prevButton.textContent = 'Previous';
                prevButton.className = 'bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md transition duration-200';
                prevButton.onclick = () => { currentPage = page - 1; loadComments(issueId, currentPage); };
                pagination.appendChild(prevButton);
            }
            if (data.next_page_url) {
                const nextButton = document.createElement('button');
                nextButton.textContent = 'Next';
                nextButton.className = 'bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md transition duration-200';
                nextButton.onclick = () => { currentPage = page + 1; loadComments(issueId, currentPage); };
                pagination.appendChild(nextButton);
            }
        })
        .catch(error => console.error('Error loading comments:', error));
    }

    function addComment(issueId) {
        const authorName = document.getElementById('author_name').value.trim();
        const body = document.getElementById('body').value.trim();
        const errorsDiv = document.getElementById('comment-modal-errors');
        errorsDiv.innerHTML = '';

        if (!authorName || !body) {
            errorsDiv.innerHTML = '<p>Please fill in all fields.</p>';
            return;
        }

        fetch(`/issues/${issueId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ issue_id: issueId, author_name: authorName, body: body })
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                let ul = document.createElement('ul');
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => {
                        let li = document.createElement('li');
                        li.textContent = error;
                        ul.appendChild(li);
                    });
                });
                errorsDiv.appendChild(ul);
            } else {
                closeCommentModal();
                loadComments(issueId, currentPage);
            }
        })
        .catch(error => {
            console.error('Error adding comment:', error);
            errorsDiv.innerHTML = '<p>Server error. Try again.</p>';
        });
    }

    function attachUser(issueId) {
        const select = document.getElementById('user-select');
        const userId = select.value;
        const userName = select.options[select.selectedIndex].text.split(' (')[0];
        const userEmail = select.options[select.selectedIndex].text.match(/\((.*?)\)/)[1];
        const errorsDiv = document.getElementById('user-modal-errors');
        errorsDiv.innerHTML = '';

        if (!userId) {
            errorsDiv.innerHTML = '<p>Select a user.</p>';
            return;
        }

        fetch(`/issues/${issueId}/users/attach`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const usersList = document.getElementById('users-list');
                const li = document.createElement('li');
                li.id = `user-${data.user.id}`;
                li.className = 'flex items-center bg-gray-700 rounded-full px-4 py-2 shadow-sm transition duration-200 hover:bg-gray-600';
                li.innerHTML = `<span class="font-semibold text-sm text-white">${data.user.name} (${data.user.email})</span> <button onclick="detachUser(${issueId}, ${data.user.id}, '${data.user.name}', '${data.user.email}')" class="font-semibold text-sm ml-2" style="color: #FF0000" onmouseover="this.style.color='#CC0000'" onmouseout="this.style.color='#FF0000'">Remove</button>`;
                usersList.appendChild(li);
                select.remove(select.selectedIndex);
                closeUserModal();
            } else {
                errorsDiv.innerHTML = '<p>Error assigning user.</p>';
            }
        })
        .catch(error => {
            console.error('Error assigning user:', error);
            errorsDiv.innerHTML = '<p>Server error. Try again.</p>';
        });
    }

    function detachUser(issueId, userId, userName, userEmail) {
        const errorsDiv = document.getElementById('user-errors');
        errorsDiv.innerHTML = '';

        fetch(`/issues/${issueId}/users/detach`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`user-${userId}`).remove();
                const select = document.getElementById('user-select');
                const option = document.createElement('option');
                option.value = userId;
                option.textContent = `${userName} (${userEmail})`;
                select.appendChild(option);
            } else {
                errorsDiv.innerHTML = '<p>Error removing user.</p>';
            }
        })
        .catch(error => {
            console.error('Error removing user:', error);
            errorsDiv.innerHTML = '<p>Server error. Try again.</p>';
        });
    }

    function attachTag(issueId) {
        const select = document.getElementById('tag-select');
        const tagId = select.value;
        const tagName = select.options[select.selectedIndex].text;
        const tagColor = select.options[select.selectedIndex].dataset.color;
        const errorsDiv = document.getElementById('tag-modal-errors');
        errorsDiv.innerHTML = '';

        if (!tagId) {
            errorsDiv.innerHTML = '<p>Select a tag.</p>';
            return;
        }

        fetch(`/issues/${issueId}/tags/attach`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tag_id: tagId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tagsList = document.getElementById('tags-list');
                const li = document.createElement('li');
                li.id = `tag-${data.tag.id}`;
                li.className = 'flex items-center bg-gray-700 rounded-full px-4 py-2 shadow-sm transition duration-200 hover:bg-gray-600';
                li.innerHTML = `<span style="background: ${data.tag.color}; padding: 2px 10px; border-radius: 12px; margin-right: 8px; font-size: 0.9rem;">${data.tag.name}</span> <button onclick="detachTag(${issueId}, ${data.tag.id}, '${data.tag.name}', '${data.tag.color}')" class="font-semibold text-sm" style="color: #FF0000" onmouseover="this.style.color='#CC0000'" onmouseout="this.style.color='#FF0000'">Remove</button>`;
                tagsList.appendChild(li);
                select.remove(select.selectedIndex);
                closeTagModal();
            } else {
                errorsDiv.innerHTML = '<p>Error attaching tag.</p>';
            }
        })
        .catch(error => {
            console.error('Error attaching tag:', error);
            errorsDiv.innerHTML = '<p>Server error. Try again.</p>';
        });
    }

    function detachTag(issueId, tagId, tagName, tagColor) {
        const errorsDiv = document.getElementById('tag-errors');
        errorsDiv.innerHTML = '';

        fetch(`/issues/${issueId}/tags/detach`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tag_id: tagId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`tag-${tagId}`).remove();
                const select = document.getElementById('tag-select');
                const option = document.createElement('option');
                option.value = tagId;
                option.textContent = tagName;
                option.dataset.color = tagColor;
                select.appendChild(option);
            } else {
                errorsDiv.innerHTML = '<p>Error detaching tag.</p>';
            }
        })
        .catch(error => {
            console.error('Error detaching tag:', error);
            errorsDiv.innerHTML = '<p>Server error. Try again.</p>';
        });
    }
</script>
@endsection
