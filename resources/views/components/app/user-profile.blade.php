@props(['user', 'roleLabel', 'initials'])
<div class="sidebar-user">
    <span class="user-avatar user-avatar--large" aria-hidden="true">{{ $initials }}</span>
    <p class="sidebar-user-name">{{ $user->name }}</p>
    <span class="role-label">{{ $roleLabel }}</span>
</div>
