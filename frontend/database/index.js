import { Database } from '@vuex-orm/core'
import User from '@/models/User'
import Company from '@/models/Company'

const database = new Database()

database.register(User)
database.register(Company)

export default database
