<div x-data="scoreBoard" class="mb-24" wire:poll.3000ms="poll">
    <div class="text-3xl mb-2 text-center">
        High Scores
    </div>

    <div
        class="space-y-2 relative overflow-y-clip"
        wire:ignore
        :style="{height: (client.users.filter((user) => user.markForDeletionAt == null).length + 1.5) * gap + 'px'}"
    >
        <template x-for="(user, index) in client.users" x-bind:key="index">
            <div class="px-4 py-2 uppercase flex w-full justify-between absolute transition-all duration-[1000ms] ease-in-out"
                x-init="() => { user.element = $el}"
            >
                <div x-text="user.client.rank_with_ordinal"></div>
                <div class="flex" x-cloak x-show="user.isCurrentUser()">
                    <div class="mr-2 relative">
                        <div class="absolute transition-all duration-500 ease-in-out scale-125" :class="{ 
                            '-left-4': user.frame == 2,
                            '-left-8': user.frame == 1,
                        }">👉</div>
                    </div>
                    <div x-text="user.name"></div>
                </div>
                <div x-cloak x-show="!user.isCurrentUser()" x-text="user.name"></div>
                <div x-text="user.client.click_count"></div>
            </div>
        </template>
    </div>
</div>


@pushOnce('scripts')
    <script>
        const gap = 50;
        let scoreBoard = () => {
            return {
                init() {
                    requestAnimationFrame(this.animate.bind(this))
                },
                animate(timeStamp) {
                    this.updateUsers(timeStamp)

                    requestAnimationFrame(this.animate.bind(this))
                },
                client: {
                    users: []
                },
                server: {
                    users: @entangle('users')
                },

                rank: null,
                totalClicks: 0,
                updateUsers(timeStamp) {
                    this.server.users.forEach((user) => {
                        let existingUser = this.client.users.find((u) => u.id == user.id)
                        if (existingUser) {
                            existingUser.server.rank = user.user_rank
                            existingUser.server.click_count = user.click_count
                            existingUser.server.rank_with_ordinal = user.rank_with_ordinal
                            existingUser.markForDeletionAt = null
                        } else {
                            this.client.users.push(new User(user))
                        }
                    })

                    this.sortUsers()
                    this.client.users.forEach((user) => {
                        if (!this.server.users.find((u) => u.id == user.id)) {
                            user.markForDeletion(timeStamp)
                        }
                    })
                    this.client.users.forEach((user) => user.setPoisition(timeStamp))
                    this.deleteUsers(timeStamp)
                },
                sortUsers() {
                    this.client.users
                        .slice()
                        .filter((user) => user.markForDeletionAt == null)
                        .sort((a, b) => a.server.rank - b.server.rank)
                        .forEach((user, index) => {
                            user.index = index
                        })
                },
                deleteUsers(timeStamp) {
                    this.client.users = this.client.users.filter((user) => {
                        return user.markForDeletionAt == null || user.markForDeletionAt > timeStamp
                    })
                }
            }
        }

        class User {
            element = null;
            gap = gap;
            currentUserId = @js(auth()->user()?->id);
            lastFrameChange = null;
            frame = 1;
            colors = {
                gold: [
                    'text-[#FFD700]', 'scale-[1.2]', 'bg-black/70', 'font-bold',
                    'after:content-["🥇"]', 'after:ml-1',
                    'before:content-["🥇"]', 'before:mr-1'
                ],
                silver: [
                    'text-[#C0C0C0]', 'scale-[1.1]', 'bg-black/70', 'font-bold',
                    'after:content-["🥈"]', 'after:ml-1',
                    'before:content-["🥈"]', 'before:mr-1'
                ],
                bronze: [
                    'text-[#CD7F32]', 'scale-[1.05]', 'bg-black/70', 'font-bold',
                    'after:content-["🥉"]', 'after:ml-1',
                    'before:content-["🥉"]', 'before:mr-1'
                ],
                normal: ['text-gray-600', 'font-semibold'],
                odd: ['bg-lopi-purple-100'],
                even: ['bg-purple-200']
            };
            constructor(user) {
                this.id = user.id
                this.name = user.name
                this.position = { top: 6000 }
                this.markForDeletionAt = null
                this.index = null
                this.client = {
                    rank: 0,
                    click_count: 0,
                    rank_with_ordinal: null
                }
                this.server = {
                    rank: user.user_rank,
                    click_count: user.click_count,
                    rank_with_ordinal: user.rank_with_ordinal
                }
            }

            setPoisition(timeStamp) {
                if (this.element) {
                    if (this.markForDeletionAt != null) {
                        this.element.classList.remove('opacity-100')
                        this.element.classList.add('opacity-0')
                        this.element.style.top = (window.innerHeight + 100) + 'px'
                    } else {
                        this.element.classList.remove('opacity-0')
                        this.element.classList.add('opacity-100')
                        this.element.style.top = (this.index * this.gap) + 'px'
                    }

                // changes frames every 200ms
                if (timeStamp - this.lastFrameChange > 500) {
                    this.frame++
                    this.lastFrameChange = timeStamp
                }

                if (this.frame > 2) {
                    this.frame = 1
                }

                if (this.client.rank != this.server.rank) {
                    this.decorateRank()
                    this.client.rank = this.server.rank
                    this.client.rank_with_ordinal = this.server.rank_with_ordinal
                }
                this.countUp()
                }
            }

            decorateRank() {
                this.element.classList.remove(...Object.values(this.colors).flat())
                if (this.server.rank == 1) {
                    this.element.classList.add(...this.colors.gold)
                } else if (this.server.rank == 2) {
                    this.element.classList.add(...this.colors.silver)
                } else if (this.server.rank == 3) {
                    this.element.classList.add(...this.colors.bronze)
                } else {
                    this.element.classList.add(...this.colors.normal)
                    if (this.index % 2 == 0) {
                        this.element.classList.add(...this.colors.even)
                    } else {
                        this.element.classList.add(...this.colors.odd)
                    }
                }

            }

            countUp() {
                if (this.client.click_count > this.server.click_count) {
                    this.client.click_count -= Math.max(1, Math.floor((this.client.click_count - this.server.click_count) / 10))
                } else if (this.client.click_count < this.server.click_count) {
                    this.client.click_count += Math.max(1, Math.floor((this.server.click_count - this.client.click_count) / 10))
                }
            }

            markForDeletion(timestamp) {
                this.markForDeletionAt = timestamp + 2000
            }

            isCurrentUser() {
                return this.id == this.currentUserId
            }
        }
    </script>
@endPushOnce