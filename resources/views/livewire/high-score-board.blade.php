<div x-data="scoreBoard" class="mb-24" wire:poll.1000ms="poll">
    <div class="text-3xl mb-2 text-center">
        High Scores
    </div>

    <div
        class="space-y-2 relative overflow-clip"
        wire:ignore
        :style="{height: client.users.filter((user) => user.markForDeletionAt == null).length * 30 + 'px'}"
    >
        <template x-for="(user, index) in client.users" x-bind:key="index">
            <div class="flex w-full justify-between absolute transition-all top-0 duration-[1000ms] ease-in-out"
                :style="{top: user.position.top + 'px', opacity: user.opacity}"
            >
                <div x-text="user.client.rank_with_ordinal"></div>
                <div x-text="user.name"></div>
                <div x-text="user.client.click_count"></div>
            </div>
        </template>
    </div>
</div>


@pushOnce('scripts')
    <script>
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
                    this.client.users.forEach((user) => user.setPoisition())
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
            constructor(user, element) {
                this.id = user.id
                this.name = user.name
                this.position = { top: 6000 }
                this.opacity = 0
                this.markForDeletionAt = null
                this.index = null
                this.client = {
                    rank: user.user_rank,
                    click_count: user.click_count,
                    rank_with_ordinal: user.rank_with_ordinal
                }
                this.server = {
                    rank: user.user_rank,
                    click_count: user.click_count,
                    rank_with_ordinal: user.rank_with_ordinal
                }
            }

            setPoisition() {
                if (this.markForDeletionAt != null) {
                    this.opacity = 0
                    this.position.top = window.innerHeight + 100
                } else {
                    this.opacity = 1
                    this.position.top = this.index * 30
                }
                this.client.rank = this.server.rank
                this.client.rank_with_ordinal = this.server.rank_with_ordinal
                this.countUp()
            }

            countUp() {
                if (this.client.click_count < this.server.click_count) {
                    this.client.click_count += 1
                } else if (this.client.click_count > this.server.click_count) {
                    this.client.click_count -= 1
                }
            }

            markForDeletion(timestamp) {
                this.markForDeletionAt = timestamp + 2000
            }
        }
    </script>
@endPushOnce