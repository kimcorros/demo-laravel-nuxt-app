import { Model } from '@vuex-orm/core'
import User from './User'

export default class Company extends Model {
  static entity = 'companies'

  static fields() {
    return {
      id: this.attr(null),
      name: this.attr(''),
      logo: this.attr(null),
      email: this.attr(''),
      users: this.hasMany(User, 'company_id'),
      created_at: this.attr(''),
      updated_at: this.attr(''),
    }
  }
}
